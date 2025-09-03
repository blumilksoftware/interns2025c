<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PreferenceRequest;
use App\Models\Preference;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render("Preferences/Preferences");
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
