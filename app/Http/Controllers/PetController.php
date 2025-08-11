<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PetController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $pets = Pet::all();

        return PetResource::collection($pets);
    }

    public function show(int|string $id): PetResource
    {
        $pet = Pet::findOrFail($id);

        return new PetResource($pet);
    }

    public function store(PetRequest $request): PetResource
    {
        $pet = Pet::create($request->validated());

        return new PetResource($pet);
    }

    public function update(PetRequest $request, int|string $id): PetResource
    {
        $pet = Pet::findOrFail($id);

        $pet->update($request->validated());

        return new PetResource($pet);
    }

    public function destroy(Pet $pet): JsonResponse
    {
        $pet->delete();

        return response()->json(null, 204);
    }
}
