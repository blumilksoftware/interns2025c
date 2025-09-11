<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PreferenceRequest;
use App\Http\Resources\PetMatchResource;
use App\Models\Pet;
use App\Models\Preference;
use App\Services\PetMatcher;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    public function show(): Response
    {
        return Inertia::render("Preferences/Preferences");
    }

    public function index(PetMatcher $matcher): Response
    {
        $user = request()->user();
        $preference = $user->preferences()->first();

        $pets = Pet::with("tags")
            ->get()
            ->map(function (Pet $pet) use ($preference, $matcher): array {
                $matchPercentage = $preference
                    ? $matcher->match($pet->toArray(), (array)($preference->preferences ?? []))
                    : 0;

                return [
                    "pet" => $pet,
                    "match" => $matchPercentage,
                ];
            })
            ->sortByDesc("match")
            ->values();

        return Inertia::render("Dashboard/Dashboard", [
            "pets" => PetMatchResource::collection($pets)->resolve(),
        ]);
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        $request->user()->preferences()->create($request->validated());

        return back()->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        $this->authorize("update", $preference);

        $preference->update($request->validated());

        return back()->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        $this->authorize("delete", $preference);

        $preference->delete();

        return back()->with("success", "Preference deleted successfully.");
    }
}
