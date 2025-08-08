<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function store(TagRequest $request): JsonResponse
    {
        $tag = Tag::query()->create($request->validated());

        return response()->json($tag, 201);
    }

    public function update(TagRequest $request, int|string $id): TagResource
    {
        $tag = Tag::findOrFail($id);

        $tag->update([
            "name" => $request->name,
        ]);

        return new TagResource($tag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(null, 204);
    }
}
