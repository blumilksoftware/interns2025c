<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FavouriteController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render("Favourites/Index", [
            "favourites" => $user->favourites()->get(),
        ]);
    }

    public function store(Request $request, Pet $pet): RedirectResponse
    {
        $user = $request->user();

        if (!$user->favourites()->where("pet_id", $pet->id)->exists()) {
            $user->favourites()->attach($pet->id);
        }

        return back()->with("success", "Pet added to favourites.");
    }

    public function destroy(Request $request, Pet $pet): RedirectResponse
    {
        $user = $request->user();

        $user->favourites()->detach($pet->id);

        return back()->with("success", "Pet removed from favourites.");
    }
}
