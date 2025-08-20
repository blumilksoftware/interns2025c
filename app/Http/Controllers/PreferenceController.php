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
        $preferences = Preference::all();

        return Inertia::render("Preferences/Index", [
            "preferences" => PreferenceResource::collection($preferences),
        ]);
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data["user_id"] = $request->user()->id;

        Preference::create($data);

        return redirect("/admin/preferences")
            ->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        $this->authorize("update", $preference);

        $preference->update($request->validated());

        return redirect("/admin/preferences")
            ->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        $this->authorize("delete", $preference);

        $preference->delete();

        return redirect("/admin/preferences")
            ->with("success", "Preference deleted successfully.");
    }
}
