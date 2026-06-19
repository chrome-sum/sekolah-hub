<?php

declare(strict_types=1);

use App\Modules\Theme\Http\Controllers\Public\HomepageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/', [HomepageController::class, 'index'])->name('public.homepage');
});
