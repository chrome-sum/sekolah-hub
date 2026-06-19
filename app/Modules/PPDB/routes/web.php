<?php

declare(strict_types=1);

use App\Modules\PPDB\Http\Controllers\Public\PublicPPDBController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/ppdb', [PublicPPDBController::class, 'index'])->name('public.ppdb.index');
    Route::get('/ppdb/daftar/{track_slug}', [PublicPPDBController::class, 'showForm'])->name('public.ppdb.register');
    Route::post('/ppdb/daftar/{track_slug}/submit', [PublicPPDBController::class, 'submit'])->name('public.ppdb.submit');
    Route::get('/ppdb/status', [PublicPPDBController::class, 'checkStatus'])->name('public.ppdb.status');
});
