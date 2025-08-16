<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetShelterAddressRequest;
use App\Models\PetShelterAddress;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class PetShelterAddressController extends Controller
{
    use AuthorizesRequests;

    public function update(PetShelterAddressRequest $request, PetShelterAddress $petShelterAddress): RedirectResponse
    {
        $this->authorize("update", $petShelterAddress);

        $petShelterAddress->update($request->validated());

        return redirect("/admin")
            ->with("success", "Pet shelter address updated successfully.");
    }

    public function destroy(PetShelterAddress $petShelterAddress): RedirectResponse
    {
        $this->authorize("delete", $petShelterAddress);

        $petShelterAddress->update([
            "address" => null,
            "city" => null,
            "postal_code" => null,
        ]);

        return redirect("/admin")
            ->with("success", "Pet shelter address deleted successfully.");
    }
}
