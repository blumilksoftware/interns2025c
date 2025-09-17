<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PreferenceRequest;
use App\Models\Pet;
use App\Models\Preference;
use App\Models\Tag;
use App\Services\GeocodingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    public function __construct(
        protected GeocodingService $geocodingService,
    ) {}

    public function show(): Response
    {
        $tags = Tag::query()
            ->withCount(["pets as accepted_pets_count" => fn($q) => $q->where("is_accepted", true)])
            ->whereHas("pets", fn($q) => $q->where("is_accepted", true), ">", 1)
            ->orderByDesc("accepted_pets_count")
            ->orderBy("name")
            ->get()
            ->map(fn(Tag $tag): array => [
                "value" => $tag->name,
                "label" => $tag->name,
                "count" => (int)$tag->accepted_pets_count,
            ]);

        $breedQuery = fn(string $species) => Pet::query()
            ->where("species", $species)
            ->where("is_accepted", true)
            ->whereNotNull("breed")
            ->where("breed", "!=", "")
            ->distinct()
            ->orderBy("breed")
            ->pluck("breed");

        $breeds = [
            "dog" => $breedQuery("dog"),
            "cat" => $breedQuery("cat"),
            "other" => $breedQuery("other"),
        ];

        $colors = Pet::query()
            ->where("is_accepted", true)
            ->whereNotNull("color")
            ->where("color", "!=", "")
            ->distinct()
            ->orderBy("color")
            ->pluck("color");

        return Inertia::render("Preferences/Preferences", [
            "tags" => $tags,
            "breeds" => $breeds,
            "colors" => $colors,
        ]);
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $preference = $request->user()->preferences()->create($data);

        $coords = $this->geocodingService->resolve(
            $data["address"] ?? null,
            $data["city"] ?? null,
            $data["postal_code"] ?? null,
        );

        if ($coords) {
            $preference->latitude = $coords["latitude"];
            $preference->longitude = $coords["longitude"];
            $preference->save();
        }

        return back()->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        $this->authorize("update", $preference);

        $data = $request->validated();

        $coords = $this->geocodingService->resolve(
            $data["address"] ?? null,
            $data["city"] ?? null,
            $data["postal_code"] ?? null,
        );

        if ($coords) {
            $preference->latitude = $coords["latitude"];
            $preference->longitude = $coords["longitude"];
        }

        $preference->update($data);

        return back()->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        $this->authorize("delete", $preference);

        $preference->delete();

        return back()->with("success", "Preference deleted successfully.");
    }
}
