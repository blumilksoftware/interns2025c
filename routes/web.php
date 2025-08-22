<?php

declare(strict_types=1);

use App\Http\Controllers\PetController;
use App\Http\Controllers\PetShelterAddressController;
use App\Http\Controllers\PetShelterController;
use App\Http\Controllers\TagController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get("/", fn() => Inertia::render("LandingPage/LandingPage", [
    "canLogin" => Route::has("login"),
    "canRegister" => Route::has("register"),
    "laravelVersion" => Application::VERSION,
    "phpVersion" => PHP_VERSION,
    "title" => __("titles.landingPage"),
]));

Route::middleware([
    "auth:sanctum",
    config("jetstream.auth_session"),
    "verified",
])->group(function (): void {
    Route::get("/dashboard", fn() => Inertia::render("Dashboard/Dashboard", [
        "title" => __("titles.dashboard"),
    ]))->name("dashboard");
    Route::get("/admin", fn() => Inertia::render("AdminPanel/AdminPanel", [
        "title" => __("titles.adminPanel"),
    ]))->name("admin");

    Route::get("/pets/static/{id}", fn(int $id) => Inertia::render("Pets/Show", [
        "title" => __("titles.dashboard"),
    ]))->name("pets.static.show");
});

Route::resource("pet-shelter-addresses", PetShelterAddressController::class)->only("store", "update", "destroy");
Route::resource("pet-shelters", PetShelterController::class)->only("index", "store", "update", "destroy");
Route::resource("pets", PetController::class)->except(["create", "edit"]);
Route::resource("tags", TagController::class)->only(["store", "update", "destroy"]);
