<?php

declare(strict_types=1);

use App\Modules\Contact\Http\Controllers\Admin\ContactController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('admin.contacts.show');
    Route::put('/contacts/{contact}/status', [ContactController::class, 'updateStatus'])->name('admin.contacts.update_status');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
});
