<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreatePetShelterAction;
use App\Http\Requests\PetShelterRequest;
use App\Http\Resources\PetShelterResource;
use App\Models\PetShelter;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PetShelterController extends Controller
{
    public function index(): Response
    {
        $shelters = PetShelter::all();

        return Inertia::render("PetShelters/Index", [
            "shelters" => PetShelterResource::collection($shelters),
        ]);
    }

    public function store(PetShelterRequest $request, CreatePetShelterAction $createPetShelterAction): RedirectResponse
    {
        $this->authorize("store", PetShelter::class);

        $data = $request->validated();

        $createPetShelterAction->execute($data);

        return back()
            ->with("success", "Pet shelter created successfully.");
    }

    public function update(PetShelterRequest $request, PetShelter $petShelter): RedirectResponse
    {
        $this->authorize("update", $petShelter);

        $petShelter->update($request->validated());

        $address = $petShelter->address;
        $address->update($request->validated());

        return back()
            ->with("success", "Pet shelter updated successfully.");
    }

    public function destroy(PetShelter $petShelter): RedirectResponse
    {
        $this->authorize("delete", $petShelter);

        $petShelter->delete();

        return back()
            ->with("success", "Pet shelter deleted successfully.");
    }
}
