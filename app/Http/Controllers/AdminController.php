<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetShelter;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render("AdminPanel/AdminPanel", [
            "pets" => Pet::query()->latest()->paginate(15),
            "shelters" => PetShelter::query()->latest()->paginate(15),
            "users" => User::query()->latest()->paginate(15),
        ]);
    }
}
