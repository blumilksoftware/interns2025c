<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PreferenceRequest;
use App\Http\Resources\PreferenceResource;
use App\Models\Preference;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    public function index(): Response
    {
        $preferences = auth()->user()->preferences()->latest()->get();

        return Inertia::render("Preferences/Index", [
            "preferences" => PreferenceResource::collection($preferences),
        ]);
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        auth()->user()->preferences()->create($request->validated());

        return redirect()->route("preferences.index")->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        if ($preference->user_id !== auth()->id()) {
            abort(403);
        }

        $preference->update($request->validated());

        return redirect()->route("preferences.index")->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        if ($preference->user_id !== auth()->id()) {
            abort(403);
        }

        $preference->delete();

        return redirect()->route("preferences.index")->with("success", "Preference deleted successfully.");
    }
}
