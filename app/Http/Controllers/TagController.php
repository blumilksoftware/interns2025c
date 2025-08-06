<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\TagRequest;

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

        return response()->json($tag, 200);
    }

    public function destroy($id)
    {
        Tag::destroy($id);
        return response()->json(null, 204);
    }
}
