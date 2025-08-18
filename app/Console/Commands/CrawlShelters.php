<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Services\GeminiService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class CrawlShelters extends Command
{
    protected $signature = "crawl:shelters {url?} {--depth=2}";
    protected $description = "Crawl shelter sites and analyze pages with AI";

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

    public function mapSpecies(?string $species): PetSpecies
    {
        if (!$species) {
            return PetSpecies::Other;
        }

        $species = mb_strtolower($species);

        return match (true) {
            str_contains($species, "pies"),
            str_contains($species, "dog") => PetSpecies::Dog,

            str_contains($species, "kot"),
            str_contains($species, "cat") => PetSpecies::Cat,

            str_contains($species, "ptak"),
            str_contains($species, "bird") => PetSpecies::Bird,

            str_contains($species, "królik"),
            str_contains($species, "rabbit") => PetSpecies::Rabbit,

            str_contains($species, "gad"),
            str_contains($species, "reptile") => PetSpecies::Reptile,

            default => PetSpecies::Other,
        };
    }

    public function mapSex(?string $sex): PetSex
    {
        if (!$sex) {
            return PetSex::Unknown;
        }

        $sex = mb_strtolower($sex);

        return match (true) {
            str_contains($sex, "samiec"),
            str_contains($sex, "pies"),
            str_contains($sex, "male") => PetSex::Male,

            str_contains($sex, "suczka"),
            str_contains($sex, "kotka"),
            str_contains($sex, "female") => PetSex::Female,

            default => PetSex::Unknown,
        };
    }

    public function mapHealthStatus(?string $status): PetHealthStatus
    {
        if (!$status) {
            return PetHealthStatus::Unknown;
        }

        $status = mb_strtolower($status);

        return match (true) {
            str_contains($status, "zdrow"),
            str_contains($status, "healthy") => PetHealthStatus::Healthy,

            str_contains($status, "chory"),
            str_contains($status, "sick") => PetHealthStatus::Sick,

            str_contains($status, "wraca"),
            str_contains($status, "recover") => PetHealthStatus::Recovering,

            str_contains($status, "ciężki"),
            str_contains($status, "critical") => PetHealthStatus::Critical,

            default => PetHealthStatus::Unknown,
        };
    }

    protected function crawlSite(Client $client, string $startUrl, GeminiService $gemini, int $maxDepth = 2): void
    {
        $queue = [[$startUrl, 0]]; // url + depth
        $visited = [];

        $parsedBase = parse_url($startUrl);
        $baseHost = $parsedBase["host"] ?? null;   // e.g. schronisko.pl

        if (!$baseHost) {
            $this->error("Invalid start URL: {$startUrl}");

            return;
        }

        while ($queue) {
            [$url, $depth] = array_shift($queue);

            if (isset($visited[$url]) || $depth > $maxDepth) {
                continue;
            }

            $visited[$url] = true;
            $this->info("Crawling (depth {$depth}): {$url}");

            try {
                $response = $client->request("GET", $url);
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
                            $baseUrl = $this->getBaseUrl($url);
                            $this->savePet($petData, $baseUrl, $url);
                        } else {
                            $this->warn("Invalid JSON from Gemini for {$url}");
                        }
                    }

                    sleep(1);
                } catch (Exception $e) {
                    $this->warn("Gemini failed: " . $e->getMessage());
                }

                // --- find links ---
                $links = $crawler->filter("a")->each(fn(Crawler $node) => $node->attr("href"));

                foreach ($links as $link) {
                    if (!$link) {
                        continue;
                    }

                    $absoluteUrl = $this->normalizeUrl($link, $url);

                    if (!$absoluteUrl) {
                        continue;
                    }

                    // check domain
                    $linkHost = parse_url($absoluteUrl, PHP_URL_HOST);

                    if ($linkHost !== $baseHost) {
                        continue;
                    }

                    // check slug rules
                    if ($this->checkIfLinkContainsRequiredUrlSlugs($absoluteUrl)) {
                        $queue[] = [$absoluteUrl, $depth + 1];
                    }
                }
            } catch (Exception|GuzzleException $e) {
                $this->error("Failed to crawl {$url}: " . $e->getMessage());
            }
        }
    }

    protected function getBaseUrl(string $url): string
    {
        $parts = parse_url($url);

        $scheme = $parts["scheme"] ?? "http";
        $host = $parts["host"] ?? "";
        $port = isset($parts["port"]) ? ":" . $parts["port"] : "";

        return "{$scheme}://{$host}{$port}/";
    }

    protected function normalizeUrl(string $url, string $baseUrl): ?string
    {
        // Skip anchors, mailto, javascript
        if (Str::startsWith($url, ["#", "javascript:", "mailto:"])) {
            return null;
        }

        // Absolute URL
        if (Str::startsWith($url, ["http://", "https://"])) {
            return $url;
        }

        // Build absolute from relative
        $baseParts = parse_url($baseUrl);
        $scheme = $baseParts["scheme"] ?? "https";
        $host = $baseParts["host"] ?? "";

        return $scheme . "://" . $host . "/" . ltrim($url, "/");
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

        foreach ($requiredUrlSlugKeywords as $keyword) {
            if (stripos($url, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function savePet(array $petData, string $shelterUrl, string $adoptionUrl): void
    {
        $shelterBaseUrl = $this->getBaseUrl($shelterUrl);

        $shelter = PetShelter::where("url", $shelterBaseUrl)->first();

        if (!$shelter) {
            $this->warn("No shelter found for {$shelterBaseUrl}");

            return;
        }

        foreach ($petData["animals"] ?? [] as $animal) {
            Pet::create([
                "name" => $animal["name"] ?? "Unknown",
                "species" => $this->mapSpecies($animal["species"] ?? null),
                "sex" => $this->mapSex($animal["gender"] ?? null),
                "breed" => $animal["breed"] ?? null,
                "age" => $animal["age"] ?? null,
                "size" => $animal["size"] ?? null,
                "weight" => $animal["weight"] ?? null,
                "color" => $animal["color"] ?? null,
                "sterilized" => $animal["sterilized"] ?? null,
                "description" => $animal["description"] ?? "",
                "health_status" => $this->mapHealthStatus($animal["health_status"] ?? null),
                "current_treatment" => $animal["current_treatment"] ?? null,
                "vaccinated" => $animal["vaccinated"] ?? null,
                "has_chip" => $animal["has_chip"] ?? null,
                "chip_number" => $animal["chip_number"] ?? null,
                "dewormed" => $animal["dewormed"] ?? null,
                "deflea_treated" => $animal["deflea_treated"] ?? null,
                "medical_tests" => $animal["medical_tests"] ?? null,
                "food_type" => $animal["food_type"] ?? null,
                "attitude_to_people" => $animal["attitude_to_people"] ?? null,
                "attitude_to_dogs" => $animal["attitude_to_dogs"] ?? null,
                "attitude_to_cats" => $animal["attitude_to_cats"] ?? null,
                "attitude_to_children" => $animal["attitude_to_children"] ?? null,
                "activity_level" => $animal["activity_level"] ?? null,
                "behavioral_notes" => $animal["behavioral_notes"] ?? null,
                "admission_date" => $animal["admission_date"] ?? null,
                "found_location" => $animal["found_location"] ?? null,
                "adoption_status" => $animal["adoption_status"] ?? null,
                "shelter_id" => $shelter->id,
                "adoption_url" => $adoptionUrl,
            ]);
        }
    }
}
