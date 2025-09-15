<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetShelterAddressRequest;
use App\Models\PetShelterAddress;
use App\Services\GeocodingService;
use Illuminate\Http\RedirectResponse;

class PetShelterAddressController extends Controller
{
    public function __construct(
        protected GeocodingService $geocodingService,
    ) {}

    public function update(PetShelterAddressRequest $request, PetShelterAddress $petShelterAddress): RedirectResponse
    {
        $this->authorize("update", $petShelterAddress);

        $data = $request->validated();

        $this->geocodingService->fillCoordinates($petShelterAddress, $data);

        $petShelterAddress->update($data);

        return back()->with("success", "Pet shelter address updated successfully.");
    }

    public function destroy(PetShelterAddress $petShelterAddress): RedirectResponse
    {
        $this->authorize("delete", $petShelterAddress);

        $petShelterAddress->update([
            "address" => null,
            "city" => null,
            "postal_code" => null,
            "latitude" => null,
            "longitude" => null,
        ]);

        return back()->with("success", "Pet shelter address deleted successfully.");
    }
}
