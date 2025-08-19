<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\PetShelter;
use App\Services\GeminiService;
use App\Services\PetService;
use App\Utils\UrlFormatHelper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class CrawlShelters extends Command
{
    protected $signature = "crawl:shelters {url?} {--depth=2}";
    protected $description = "Crawl shelter sites and analyze pages with AI";

    public function __construct(
        protected PetService $petService,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $gemini = new GeminiService();
        $petSheltersWithExistingUrl = PetShelter::whereNotNull("url")->get();

        if ($argumentUrl = $this->argument("url")) {
            $petShelterUrls = collect([(object)["url" => $argumentUrl, "id" => null]]);
        } else {
            $petShelterUrls = $petSheltersWithExistingUrl->toArray();
        }

        $client = new Client([
            "timeout" => 120,
            "verify" => false,
        ]);

        $maxDepth = (int)$this->option("depth");

        foreach ($petShelterUrls as $url) {
            $urlString = is_object($url) ? $url->url : $url["url"];
            $this->crawlSite($client, $urlString, $gemini, $maxDepth);
        }
    }

    protected function crawlSite(Client $client, string $startUrl, GeminiService $gemini, int $maxDepth = 2): void
    {
        $queue = [[$startUrl, 0]]; // url + depth
        $visited = [];

        $parsedBase = parse_url($startUrl);
        $baseHost = $parsedBase["host"] ?? null;

        if (!$baseHost) {
            $this->error("Invalid start URL: $startUrl");

            return;
        }

        while ($queue) {
            [$adoptionUrl, $depth] = array_shift($queue);

            if (isset($visited[$adoptionUrl]) || $depth > $maxDepth) {
                continue;
            }

            $visited[$adoptionUrl] = true;
            $this->info("Crawling (depth $depth): $adoptionUrl");

            try {
                $response = $client->request("GET", $adoptionUrl);
                $html = (string)$response->getBody();
                $crawler = new Crawler($html);

                // --- process page ---
                $getWebpageClearBodyText = $this->clearHtmlUnnecessaryTags($crawler);
                $altAndIcons = $this->getAltAndIconTags($crawler);

                $fullPayload = $getWebpageClearBodyText .
                    "\n\nImage Alts: " . implode(", ", $altAndIcons["images"]) .
                    "\nIcon Titles: " . implode(", ", $altAndIcons["icons"]) .
                    "\nSVG Labels: " . implode(", ", $altAndIcons["svgs"]);

                $prompt = config("prompts.crawl_shelters");
                $payload = [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => "Prompt: $prompt , Page content: $fullPayload"],
                            ],
                        ],
                    ],
                ];

                try {
                    $result = $gemini->generateContent($payload);
                    $raw = $result["candidates"][0]["content"]["parts"][0]["text"] ?? null;

                    if ($raw) {
                        $jsonClean = preg_replace("/^```(json)?|```$/m", "", trim($raw));
                        $this->line($jsonClean);

                        $petData = json_decode($jsonClean, true);

                        if (json_last_error() === JSON_ERROR_NONE && is_array($petData)) {
                            $baseUrl = UrlFormatHelper::getBaseUrl($adoptionUrl);
                            $this->petService->savePetToDB($petData, $baseUrl, $adoptionUrl);
                        } else {
                            $this->warn("Invalid JSON from Gemini for $adoptionUrl");
                        }
                    }

                    sleep(1);
                } catch (Exception $e) {
                    $this->warn("Gemini failed: " . $e->getMessage());
                }

                $links = $crawler->filter("a")->each(fn(Crawler $node) => $node->attr("href"));

                foreach ($links as $link) {
                    if (!$link) {
                        continue;
                    }

                    $absoluteUrl = UrlFormatHelper::normalizeUrl($link, $adoptionUrl);

                    if (!$absoluteUrl) {
                        continue;
                    }

                    $linkHost = parse_url($absoluteUrl, PHP_URL_HOST);

                    if ($linkHost !== $baseHost) {
                        continue;
                    }

                    if ($this->checkIfLinkContainsRequiredUrlSlugs($absoluteUrl)) {
                        $queue[] = [$absoluteUrl, $depth + 1];
                    }
                }
            } catch (Exception|GuzzleException $e) {
                $this->error("Failed to crawl $adoptionUrl: " . $e->getMessage());
            }
        }
    }

    protected function clearHtmlUnnecessaryTags(Crawler $crawler): string
    {
        $bodyCrawler = $crawler->filter("body");
        $bodyCrawler->filter("script, style, .ads, .sidebar")->each(
            fn(Crawler $node) => $node->getNode(0)->parentNode->removeChild($node->getNode(0)),
        );

        return trim($bodyCrawler->text());
    }

    protected function getAltAndIconTags(Crawler $crawler): array
    {
        $bodyCrawler = $crawler->filter("body");
        $bodyCrawler->filter("script, style, .ads, .sidebar")->each(
            fn(Crawler $node) => $node->getNode(0)->parentNode->removeChild($node->getNode(0)),
        );

        return [
            "images" => $bodyCrawler->filter("img[alt]")->each(fn(Crawler $node) => trim($node->attr("alt"))),
            "icons" => array_filter($bodyCrawler->filter('i[class*="icon-"], span[class*="icon-"]')
                ->each(fn(Crawler $node) => trim($node->attr("title") ?? ""))),
            "svgs" => $bodyCrawler->filter("svg[aria-label]")
                ->each(fn(Crawler $node) => trim($node->attr("aria-label"))),
        ];
    }

    protected function checkIfLinkContainsRequiredUrlSlugs(string $url): bool
    {
        $requiredUrlSlugKeywords = [
            "do-adopcji", "zwierzak", "psy", "pies", "koty", "kot", "adopcja", "adoptuj",
            "schronisko", "filter", "zwierze", "adopcje", "zwierzeta", "szczegoly", "catalog",
            "psy-do-adopcji", "koty-do-adopcji", "adoptuj-zwierzaka", "adoptuj-psa", "adoptuj-kota",
            "kacik-adopcyjny",
        ];

        return array_any($requiredUrlSlugKeywords, fn($keyword) => stripos($url, $keyword) !== false);
    }
}
