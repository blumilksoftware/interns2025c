<?php

declare(strict_types=1);

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use Inertia\Response;

Route::get("/", fn(): Response => inertia("LandingPage"));
Route::resource("tags", TagController::class)->only(["store", "update", "destroy"]);
