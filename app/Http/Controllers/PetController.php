<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Http\Resources\PetIndexResource;
use App\Http\Resources\PetShowResource;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
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

        $pet = Pet::query()->create($request->validated());

        if ($request->has("tags")) {
            $pet->tags()->sync($request->input("tags"));
        }

        return redirect()->route("pets.index")->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $this->authorize("update", $pet);

        $tags = $request->input("tags", []);
        $petData = $request->except("tags");

        $pet->update($petData);

        if (!empty($tags)) {
            $pet->tags()->sync($tags);
        }

        return redirect()->route("pets.index")
            ->with("success", "Pet updated successfully.");
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        $this->authorize("delete", $pet);

        $pet->delete();

        return redirect()->route("pets.index")
            ->with("success", "Pet deleted successfully.");
    }
}
