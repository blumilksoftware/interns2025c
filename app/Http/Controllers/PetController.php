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
        return Inertia::render("Pets/Show", [
            "pet" => new PetShowResource($pet),
        ]);
    }

    public function store(PetRequest $request): RedirectResponse
    {
        $this->authorize("store", Pet::class);

        Pet::query()->create($request->validated());

        return back()->with("success", "Pet created successfully.");
    }

    public function update(PetRequest $request, int $pet): RedirectResponse
    {
        $petModel = Pet::query()->withoutGlobalScope("is_accepted")->findOrFail($pet);

        $this->authorize("update", $petModel);

        $petModel->update($request->validated());

        return back()->with("success", "Pet updated successfully.");
    }

    public function destroy(int $pet): RedirectResponse
    {
        $petModel = Pet::query()->withoutGlobalScope("is_accepted")->findOrFail($pet);

        $this->authorize("delete", $petModel);

        $petModel->delete();

        return back()->with("success", "Pet deleted successfully.");
    }
}
