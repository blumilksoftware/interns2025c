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

        return Inertia::render("Pets/Index", [
            "pets" => PetIndexResource::collection($pets),
        ]);
    }

    public function show(Pet $pet): Response
    {
        return Inertia::render("Pets/Show", [
            "pet" => new PetShowResource($pet),
        ]);
    }

    public function store(PetRequest $request): RedirectResponse
    {
        $this->authorize("store", Pet::class);

        Pet::query()->create($request->validated());

        return redirect()->route("pets.index")->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $this->authorize("update", $pet);

        $pet->update($request->validated());

        return redirect()->route("pets.index")->with("success", "Pet updated successfully.");
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        $this->authorize("delete", $pet);

        $pet->delete();

        return redirect()->route("pets.index")
            ->with("success", "Pet deleted successfully.");
    }
}
