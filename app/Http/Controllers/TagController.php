<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function store(TagRequest $request): RedirectResponse
    {
        $this->authorize("store", Tag::class);

        Tag::query()->create($request->validated());

        return redirect("/admin")
            ->with("success", "Tag created successfully.");
    }

    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        $this->authorize("update", $tag);

        $tag->update($request->validated());

        return redirect("/admin")
            ->with("success", "Tag updated successfully.");
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize("delete", $tag);

        $tag->delete();

        return redirect("/admin")
            ->with("success", "Tag deleted successfully.");
    }
}
