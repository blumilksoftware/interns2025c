<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
            "shelters" => PetShelterResource::collection($shelters)->resolve(),
        ]);
    }

    public function store(PetShelterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $addressData = collect($data)
            ->only(["address", "city", "postal_code"])
            ->toArray();

        $shelter = PetShelter::query()->create($data);

        if (!empty($addressData["address"])) {
            $shelter->address()->create($addressData);
        }

        return redirect("/admin")
            ->with("success", "Pet shelter created successfully.");
    }

    public function update(PetShelterRequest $request, PetShelter $petShelter): RedirectResponse
    {
        $petShelter->update($request->validated());

        return redirect("/admin")
            ->with("success", "Pet shelter updated successfully.");
    }

    public function destroy(PetShelter $petShelter): RedirectResponse
    {
        $petShelter->delete();

        return redirect("/admin")
            ->with("success", "Pet shelter deleted successfully.");
    }
}
