<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "pet_id" => ["required", "exists:pets,id"],
        ]);

        $pet = Pet::findOrFail($validated["pet_id"]);
        $user = $request->user();

        $this->authorize("favouriteCreate", $pet);

        $user->favourites()->attach($pet->id);

        return back()->with("success", "Pet added to favourites.");
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        $user = auth()->user();

        $this->authorize("favouriteDelete", $pet);

        $user->favourites()->detach($pet->id);

        return back()->with("success", "Pet removed from favourites.");
    }
}
