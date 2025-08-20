<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $users = User::query()->latest()->paginate(15);

        return Inertia::render("Users/Index", [
            "users" => UserResource::collection($users),
        ]);
    }

    public function show(User $user): Response
    {
        return Inertia::render("Users/Show", [
            "user" => new UserResource($user),
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->authorize("store", User::class);

        User::query()->create($request->validated());

        return redirect()->route("users.index")->with("success", "User created successfully.");
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize("update", $user);

        $user->update($request->validated());

        return redirect()->route("users.index")->with("success", "User updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize("delete", $user);

        $user->delete();

        return redirect()->route("users.index")
            ->with("success", "User deleted successfully.");
    }
}
