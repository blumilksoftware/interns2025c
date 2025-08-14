<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    use AuthorizesRequests;

    public function store(TagRequest $request): TagResource
    {
        $this->authorize("store", Tag::class);
        $tag = Tag::query()->create($request->validated());

        return new TagResource($tag)->additional([
            "message" => "Tag created successfully",
        ]);
    }

    public function update(TagRequest $request, Tag $tag): TagResource
    {
        $this->authorize("update", $tag);

        $tag->update($request->validated());

        return new TagResource($tag)->additional([
            "message" => "Tag updated successfully",
        ]);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $this->authorize("delete", $tag);
        $tag->delete();

        return response()->json([
            "message" => "Tag deleted successfully",
        ]);
    }
}
