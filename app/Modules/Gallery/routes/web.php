<?php

declare(strict_types=1);

use App\Modules\Gallery\Http\Controllers\Public\PublicGalleryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('public.gallery.index');
    Route::get('/galeri/{slug}', [PublicGalleryController::class, 'show'])->name('public.gallery.show');
});
