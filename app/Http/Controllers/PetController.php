<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    public function index(): Response
    {
        $pets = Pet::all();

        return Inertia::render("Pets/Index", ["pets" => $pets]);
    }

    public function show(int $id): Response
    {
        $pet = Pet::findOrFail($id);

        return Inertia::render("Pets/Show", ["pet" => $pet]);
    }

    public function store(PetRequest $request): RedirectResponse
    {
        Pet::create($request->validated());

        return redirect()->route("pets.index")->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, int $id): RedirectResponse
    {
        $pet = Pet::findOrFail($id);
        $pet->update($request->validated());

        return redirect()->route("pets.index")->with("success", "Pet updated successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $pet = Pet::findOrFail($id);
        $pet->delete();

        return redirect()->route("pets.index")->with("success", "Pet deleted successfully.");
    }
}
