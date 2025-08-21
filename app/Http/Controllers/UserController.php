<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
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
        $user = $request->user();
        return Inertia::render("Profile/Show", [
            "user" => new UserResource( $user),
        ]);
    }

    public function destroy(DeleteUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize("delete", $user);
        $user->petShelters()->detach();

        $user->delete();

        return back()->with("success", "User deleted successfully.");
    }
}
