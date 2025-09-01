<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\PetAdminResource;
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
        $pets = Pet::query()->latest()->paginate(15);
        $incomingPetsRequests = Pet::query()->withoutGlobalScope("is_accepted")->where("is_accepted", false)->latest()->paginate(15);

        return Inertia::render("AdminPanel/AdminPanel", [
            "pets" => PetAdminResource::collection($pets),
            "incomingPetsRequests" => PetAdminResource::collection($incomingPetsRequests),
            "shelters" => PetShelter::query()->latest()->paginate(15),
            "users" => User::query()->latest()->paginate(15),
            "user" => auth()->user(),
        ]);
    }

    public function acceptPet(int $id): RedirectResponse
    {
        $pet = Pet::query()->withoutGlobalScope("is_accepted")->findOrFail($id);
        $pet->update(["is_accepted" => true]);

        return back()->with("success", "Pet accepted successfully");
    }

    public function rejectPet(int $id): RedirectResponse
    {
        $pet = Pet::query()->withoutGlobalScope("is_accepted")->findOrFail($id);
        $pet->delete();

        return back()->with("success", "Pet rejected and deleted successfully");
    }
}
