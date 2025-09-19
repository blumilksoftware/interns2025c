<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\FavouriteRequest;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;

class FavouriteController extends Controller
{
    public function store(FavouriteRequest $request): RedirectResponse
    {
        $pet = Pet::findOrFail($request->validated()["pet_id"]);
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
