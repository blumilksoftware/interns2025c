<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Http\Resources\PetIndexResource;
use App\Http\Resources\PetShowResource;
use App\Models\Pet;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    public function __construct(
        private TagService $tagService,
    ) {}

    public function index(): Response
    {
        $pets = Pet::query()->latest()->paginate(15);

        return Inertia::render("Dashboard/Dashboard", [
            "pets" => PetIndexResource::collection($pets),
        ]);
    }

    public function show(Pet $pet): Response
    {
        $pet->load("tags");

        return Inertia::render("Pets/Show", [
            "pet" => new PetShowResource($pet),
        ]);
    }

    public function store(PetRequest $request): RedirectResponse
    {
        $this->authorize("store", Pet::class);

        $validatedData = $request->validated()->except("tags");
        $pet = Pet::query()->create($validatedData);

        $this->syncTags($pet, $request->input("tags", []));

        return back()->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $this->authorize("update", $pet);

        $validatedData = $request->validated()->except("tags");
        $pet->update($validatedData);

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
