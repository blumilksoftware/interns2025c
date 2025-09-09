<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TagRequest;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function __construct(
        private TagService $tagService,
    ) {}

    public function store(TagRequest $request): RedirectResponse
    {
        $this->authorize("store", Tag::class);

        $sanitizedName = $this->tagService->sanitizeTagName($request->input("name"));

        if ($sanitizedName) {
            Tag::query()->firstOrCreate(["name" => $sanitizedName]);

            return back()
                ->with("success", "Tag created successfully.");
        }

        return redirect("/admin")
            ->with("error", "Invalid tag name - only letters are allowed.");
    }

    public function update(TagRequest $request, Tag $tag): RedirectResponse
    {
        $this->authorize("update", $tag);

        $sanitizedName = $this->tagService->sanitizeTagName($request->input("name"));

        if ($sanitizedName) {
            $tag->update(["name" => $sanitizedName]);

            return back()
                ->with("success", "Tag updated successfully.");
        }

        return back()
            ->with("error", "Invalid tag name - only letters are allowed.");
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize("delete", $tag);

        $tag->delete();

        return back()
            ->with("success", "Tag deleted successfully.");
    }
}
