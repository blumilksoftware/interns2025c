<?php

declare(strict_types=1);

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;
use Inertia\Response;

Route::get('/', fn(): Response => inertia('LandingPage'));

Route::resource('pets', PetController::class);
