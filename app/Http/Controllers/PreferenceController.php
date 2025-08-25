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
        return Inertia::render("Dashboard/Dashboard");
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        auth()->user()->preferences()->create($request->validated());

        return redirect()->back()->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        if ($preference->user_id !== $request->user()->id) {
            abort(403);
        }

        $preference->update($request->validated());

        return redirect()->back()->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        if ($preference->user_id !== auth()->id()) {
            abort(403);
        }

        $preference->delete();

        return redirect()->back()->with("success", "Preference deleted successfully.");
    }
}
