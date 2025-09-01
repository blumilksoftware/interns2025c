<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Services\PetMatcher;
use App\Http\Requests\PreferenceRequest;
use App\Models\Preference;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    public function index(PetMatcher $matcher): Response
    {
        $user = request()->user();
        $preference = $user->preferences()->first();

        $pets = Pet::with('tags')->get()->map(function (Pet $pet) use ($preference, $matcher) {
            $petData = $pet->toArray();
            $petData['tags'] = $pet->tags->map(fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])->toArray();

            $matchPercentage = $preference
                ? $matcher->match($petData, $preference->preferences)
                : 0;

            return [
                'pet' => $pet,
                'match' => $matchPercentage,
            ];
        })->sortByDesc('match')->values();

        return Inertia::render('Dashboard/Dashboard', [
            'pets' => $pets,
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
