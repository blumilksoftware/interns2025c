<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inertia\Response;
use App\Http\Controllers\TagController;

Route::get("/", fn(): Response => inertia("Welcome"));
Route::apiResource('tags', TagController::class)->only(['store', 'update', 'destroy']);
