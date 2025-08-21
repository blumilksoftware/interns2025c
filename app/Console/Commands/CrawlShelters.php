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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class CrawlShelters extends Command
{
    protected $signature = "crawl:shelters {url?} {--depth=2} {--additional-urls=}";
    protected $description = "Crawl shelter sites and analyze pages with AI";

    public function __construct(
        protected PetService $petService,
        protected GeminiService $gemini,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $petSheltersWithExistingUrl = PetShelter::whereNotNull("url")->get();

        if ($argumentUrl = $this->argument("url")) {
            $petShelterUrls = collect([(object)["url" => $argumentUrl, "id" => null]]);
        } else {
            $petShelterUrls = collect($petSheltersWithExistingUrl->toArray());
        }

        if ($additionalUrls = $this->option("additional-urls")) {
            $additionalUrlsArray = array_map("trim", explode(",", $additionalUrls));
            $additionalObjects = array_map(fn($u) => (object)["url" => $u, "id" => null], $additionalUrlsArray);

            $existingUrls = $petShelterUrls->pluck("url")->all();

            foreach ($additionalObjects as $obj) {
                if (!in_array($obj->url, $existingUrls, true)) {
                    $petShelterUrls->push($obj);
                }
            }
        }

        $client = new Client([
            "timeout" => 120,
            "verify" => false,
        ]);

        $maxDepth = (int)$this->option("depth");

        foreach ($petShelterUrls as $index => $url) {
            $urlString = is_object($url) ? $url->url : $url["url"];

            $this->info("Processing shelter " . ($index + 1) . " of " . count($petShelterUrls) . ": $urlString");

            $this->crawlSite($client, $urlString, $maxDepth);

            $this->info("Completed crawling: $urlString");
            $this->line("---");

            if ($index < count($petShelterUrls) - 1) {
                sleep(2);
            }
        }
    }

    protected function crawlSite(Client $client, string $startUrl, int $maxDepth = 2): void
    {
        $queue = [[$startUrl, 0]]; // url + depth
        $visited = [];

        $baseHost = UrlFormatHelper::getUrlHost($startUrl);

        if (!$baseHost) {
            $this->error("Invalid start URL: $startUrl");

            return;
        }

        while ($queue) {
            [$adoptionUrl, $depth] = array_shift($queue);

            if (isset($visited[$adoptionUrl]) || $depth > $maxDepth) {
                continue;
            }

            if ($this->checkIfLinkContainsAntiKeywords($adoptionUrl)) {
                $this->info("Contains anti-keywords: $adoptionUrl - Skipping");
                $visited[$adoptionUrl] = true;

                continue;
            }

            $visited[$adoptionUrl] = true;
            $this->info("Crawling (depth $depth): $adoptionUrl");
            Log::info("Crawling page: $adoptionUrl");
            $html = Cache::remember("crawl_page:" . md5($adoptionUrl), now()->addHours(24), function () use ($client, $adoptionUrl) {
                try {
                    $response = $client->request("GET", $adoptionUrl);

                    return (string)$response->getBody();
                } catch (GuzzleException $e) {
                    Log::warning("HTTP request failed for $adoptionUrl: " . $e->getMessage());

                    return;
                }
            });

            if (!$html) {
                $this->warn("Skipping $adoptionUrl due to failed HTTP request or cache.");

                continue;
            }

            $crawler = new Crawler($html);

            $getWebpageClearBodyText = $this->clearHtmlUnnecessaryTags($crawler);

            if (!$this->isLikelyAPetPage($getWebpageClearBodyText, 6) && $depth > 0) {
                $this->info("Page does not appear to be specific pet page: $adoptionUrl - Skipping");

                continue;
            }
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
                $result = $this->gemini->generateContent($payload);
                $raw = $result["candidates"][0]["content"]["parts"][0]["text"] ?? null;

                if ($raw) {
                    $jsonClean = preg_replace("/^```(json)?|```$/m", "", trim($raw));
                    $this->line($jsonClean);

                    $petData = json_decode($jsonClean, true);

                    if (json_last_error() === JSON_ERROR_NONE && is_array($petData)) {
                        $baseUrl = UrlFormatHelper::getBaseUrl($adoptionUrl);
                        $this->petService->store($petData, $baseUrl, $adoptionUrl);
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

                $queue[] = [$absoluteUrl, $depth + 1];
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

    protected function isLikelyAPetPage(string $body, ?int $threshold = null): bool
    {
        $body = html_entity_decode($body, ENT_QUOTES | ENT_HTML5);
        $bodyLower = mb_strtolower($body);
        $bodyAscii = Str::ascii($bodyLower);

        $petKeywords = array_values(array_filter(array_unique(config("crawl_keywords.pet_keywords", []))));
        $requiredKeywords = array_values(array_filter(array_unique(config("crawl_keywords.required_keywords", []))));

        $allKeywords = array_merge($petKeywords, $requiredKeywords);

        $allKeywordsLower = array_map(fn($k) => mb_strtolower($k), $allKeywords);
        $allKeywordsAscii = array_map(fn($k) => Str::ascii(mb_strtolower($k)), $allKeywordsLower);

        if ($threshold === null) {
            $threshold = min(5, count($allKeywords));
        }

        $score = 0;
        $matchedKeywords = [];

        foreach ($allKeywordsLower as $i => $kwLower) {
            if ($kwLower === "") {
                continue;
            }

            if ((stripos($bodyLower, $kwLower) !== false || stripos($bodyAscii, $allKeywordsAscii[$i]) !== false)
                && !in_array($kwLower, $matchedKeywords, true)
            ) {
                $score++;
                $matchedKeywords[] = $kwLower;
            }
        }

        return $score >= $threshold;
    }

    protected function checkIfLinkContainsAntiKeywords(string $url): bool
    {
        $antiKeywords = config("crawl.anti_keywords");

        return collect($antiKeywords)->contains(fn($keyword) => stripos($url, $keyword) !== false);
    }
}
