<?php

declare(strict_types=1);

use App\Http\Controllers\PetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get("/", fn() => Inertia::render("LandingPage", [
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
    Route::get("/dashboard", fn() => Inertia::render("Dashboard/Dashboard", [
        "title" => __("titles.dashboard"),
    ]))->name("dashboard");
    Route::get("/admin", fn() => Inertia::render("AdminPanel/AdminPanel", [
        "title" => __("titles.adminPanel"),
    ]))->name("admin");
});

Route::resource("pets", PetController::class)->except(["create", "edit"]);