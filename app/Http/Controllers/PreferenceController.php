<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PreferenceRequest;
use App\Http\Resources\PetMatchResource;
use App\Models\Pet;
use App\Models\Preference;
use App\Models\Tag;
use App\Services\PetMatcher;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
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

    public function index(PetMatcher $matcher): Response
    {
        $user = request()->user();
        $preference = $user->preferences()->first();

        $pets = Pet::with(["tags", "shelter.address"])
            ->where("is_accepted", true)
            ->get()
            ->map(function (Pet $pet) use ($preference, $matcher): array {
                $matchPercentage = $preference
                    ? $matcher->match($pet->toArray(), (array)($preference->preferences ?? []))
                    : 0;

                return [
                    "pet" => $pet,
                    "match" => $matchPercentage,
                ];
            })
            ->sortByDesc("match")
            ->values();

        return Inertia::render("Dashboard/Dashboard", [
            "pets" => PetMatchResource::collection($pets)->resolve(),
        ]);
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        $request->user()->preferences()->create($request->validated());

        return back()->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        $this->authorize("update", $preference);

        $preference->update($request->validated());

        return back()->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        $this->authorize("delete", $preference);

        $preference->delete();

        return back()->with("success", "Preference deleted successfully.");
    }
}
