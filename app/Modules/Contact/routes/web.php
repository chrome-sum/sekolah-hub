<?php

declare(strict_types=1);

use App\Modules\Contact\Http\Controllers\Public\PublicContactController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/kontak', [PublicContactController::class, 'showForm'])->name('public.contact.show');
    Route::post('/kontak', [PublicContactController::class, 'submit'])->name('public.contact.submit');
});
