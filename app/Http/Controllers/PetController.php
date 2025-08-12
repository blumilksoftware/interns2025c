<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    public function index(): Response
    {
        $pets = Pet::all();
        $petsTransformed = PetResource::collection($pets);

        return Inertia::render("Pets/Index", [
            "pets" => $petsTransformed->resolve(),
        ]);
    }

    public function show(Pet $pet): Response
    {
        return Inertia::render("Pets/Show", [
            "pet" => (new PetResource($pet))->resolve(),
        ]);
    }

    public function store(PetRequest $request): RedirectResponse
    {
        Pet::create($request->validated());

        return redirect()->route("pets.index")->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $pet->update($request->validated());

        return redirect()->route("pets.index")->with("success", "Pet updated successfully.");
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        $pet->delete();

        return redirect()->route("pets.index")->with("success", "Pet deleted successfully.");
    }
}
