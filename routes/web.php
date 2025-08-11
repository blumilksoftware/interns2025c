<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Response;

Route::get("/", fn(): Response => inertia("LandingPage"));
Route::get("/admin", fn(): Response => inertia("AdminPanel/AdminPanel"));
