<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PetAdminResource;
use App\Http\Resources\PetShelterAdminResource;
use App\Http\Resources\UserAdminResource;
use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        $pets = Pet::query()->with("tags")->where("is_accepted", true)->get();
        $incomingPetsRequests = Pet::query()->with("tags")->where("is_accepted", false)->get();
        $shelters = PetShelter::query()->with("address")->get();
        $users = User::query()->get();

        return Inertia::render("AdminPanel/AdminPanel", [
            "pets" => PetAdminResource::collection($pets),
            "incomingPetsRequests" => PetAdminResource::collection($incomingPetsRequests),
            "shelters" => PetShelterAdminResource::collection($shelters),
            "users" => UserAdminResource::collection($users),
        ]);
    }

    public function acceptPet(Pet $pet): RedirectResponse
    {
        $this->authorize("accept", $pet);

        $pet->update(["is_accepted" => true]);

        return back()->with("success", "Pet accepted successfully");
    }

    public function rejectPet(Pet $pet): RedirectResponse
    {
        $this->authorize("reject", $pet);

        $pet->delete();

        return back()->with("success", "Pet rejected and deleted successfully");
    }
}
