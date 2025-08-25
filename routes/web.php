<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PetShelterAddressController;
use App\Http\Controllers\PetShelterController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get("/", fn() => Inertia::render("LandingPage/LandingPage", [
    "canLogin" => Route::has("login"),
    "canRegister" => Route::has("register"),
    "laravelVersion" => Application::VERSION,
    "phpVersion" => PHP_VERSION,
]));

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function (): void {
    Route::get("/users/{user}", [UserController::class, "show"])->name("users.show");
    Route::get("/profile", [UserController::class, "profile"])->name("users.profile");
    Route::delete("/users/{user}", [UserController::class, "destroy"])->name("users.destroy");
    Route::get("/dashboard", [PetController::class, "index"])->name("dashboard");
});

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
    AdminMiddleware::class,
])->group(function (): void {
    Route::get("/admin", [AdminController::class, "index"])->name("admin");
});

Route::resource("pet-shelter-addresses", PetShelterAddressController::class)
    ->only("store", "update", "destroy");

Route::resource("pet-shelters", PetShelterController::class)
    ->only("index", "store", "update", "destroy");

Route::resource("pets", PetController::class)
    ->except(["create", "edit"]);

Route::resource("tags", TagController::class)
    ->only(["store", "update", "destroy"]);
