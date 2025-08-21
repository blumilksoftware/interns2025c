<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function profile(Request $request): Response
    {
        return Inertia::render("Profile/Show", [
            "user" => new UserResource($request->user()),
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize("delete", $user);
        $user->petShelters()->detach();

        $user->delete();

        return redirect()->back()->with("success", "User deleted successfully.");
    }
}
