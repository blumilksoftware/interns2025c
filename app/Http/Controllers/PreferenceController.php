<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\FindPetsForPreferenceAction;
use App\Http\Requests\PreferenceRequest;
use App\Http\Resources\PetMatchResource;
use App\Models\Preference;
use App\Services\GeocodingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PreferenceController extends Controller
{
    public function __construct(
        protected GeocodingService $geocodingService,
    ) {}

    public function index(FindPetsForPreferenceAction $findPetsForPreference): Response
    {
        $user = request()->user();
        $preference = $user->preferences()->first();

        $pets = $findPetsForPreference->execute($preference);

        return Inertia::render("Dashboard/Dashboard", [
            "pets" => PetMatchResource::collection($pets)->resolve(),
        ]);
    }

    public function store(PreferenceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $preference = $request->user()->preferences()->create($data);

        if (!empty($data["city"])) {
            $this->geocodingService->fillCoordinates($preference, [
                "address" => $data["city"],
                "city" => $data["city"],
            ]);
        }

        return back()->with("success", "Preference created successfully.");
    }

    public function update(PreferenceRequest $request, Preference $preference): RedirectResponse
    {
        $this->authorize("update", $preference);

        $data = $request->validated();

        if (!empty($data["city"])) {
            $this->geocodingService->fillCoordinates($preference, [
                "address" => $data["city"], 
                "city" => $data["city"],
            ]);
        }

        $preference->update($data);

        return back()->with("success", "Preference updated successfully.");
    }

    public function destroy(Preference $preference): RedirectResponse
    {
        $this->authorize("delete", $preference);

        $preference->delete();

        return back()->with("success", "Preference deleted successfully.");
    }
}
