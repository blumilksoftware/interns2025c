<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function store(TagRequest $request)
    {
        $tag = Tag::query()->create($request->validated());
        return response()->json($tag, 201);
    }

    public function update(TagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $tag->update([
            'name' => $request->name,
        ]);

        return new TagResource($tag);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(null, 204);
    }
}
