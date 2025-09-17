<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Http\Resources\PetIndexResource;
use App\Http\Resources\PetShowResource;
use App\Models\Pet;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    public function __construct(
        private TagService $tagService,
    ) {}

    public function index(Request $request): Response
    {
        $perDog = 20;
        $perCat = 20;
        $perOther = 10;

        $dogs = Pet::query()
            ->with("tags")
            ->where("is_accepted", true)
            ->whereRaw("LOWER(TRIM(species)) = ?", ["dog"])
            ->orderByDesc("id")
            ->take($perDog)
            ->get();

        $cats = Pet::query()
            ->with("tags")
            ->where("is_accepted", true)
            ->whereRaw("LOWER(TRIM(species)) = ?", ["cat"])
            ->orderByDesc("id")
            ->take($perCat)
            ->get();

        $others = Pet::query()
            ->with("tags")
            ->where("is_accepted", true)
            ->whereRaw("LOWER(TRIM(species)) = ?", ["other"])
            ->orderByDesc("id")
            ->take($perOther)
            ->get();

        $pets = $dogs->merge($cats)->merge($others)->shuffle()->values();

        return Inertia::render("Dashboard/Dashboard", [
            "pets" => PetIndexResource::collection($pets),
        ]);
    }

    public function show(Pet $pet): Response
    {
        $pet->load(["tags", "shelter.address"]);

        return Inertia::render("Pets/Show", [
            "pet" => new PetShowResource($pet),
        ]);
    }

    public function store(PetRequest $request): RedirectResponse
    {
        $this->authorize("store", Pet::class);

        $pet = Pet::query()->create($request->validated());

        $this->syncTags($pet, $request->input("tags", []));

        return back()->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $this->authorize("update", $pet);

        $pet->update($request->except("tags"));

        $this->syncTags($pet, $request->input("tags", []));

        return back()->with("success", "Pet updated successfully.");
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        $this->authorize("delete", $pet);

        $pet->delete();

        return back()->with("success", "Pet deleted successfully.");
    }

    private function syncTags(Pet $pet, array $tags): void
    {
        if (empty($tags)) {
            $pet->tags()->sync([]);

            return;
        }

        $tagIds = $this->tagService->processTagsAndGetIds($tags);
        $pet->tags()->sync($tagIds);
    }
}
