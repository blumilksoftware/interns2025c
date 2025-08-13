<?php

declare(strict_types=1);

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
    Route::get("/dashboard", fn() => Inertia::render("Dashboard/Dashboard"))->name("dashboard");
    Route::get("/admin", fn() => Inertia::render("AdminPanel/AdminPanel"))->name("admin");
});

