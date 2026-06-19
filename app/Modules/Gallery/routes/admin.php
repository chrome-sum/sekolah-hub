<?php

declare(strict_types=1);

use App\Modules\Gallery\Http\Controllers\Admin\GalleryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::resource('gallery', GalleryController::class)->names([
        'index' => 'admin.gallery.index',
        'create' => 'admin.gallery.create',
        'store' => 'admin.gallery.store',
        'edit' => 'admin.gallery.edit',
        'update' => 'admin.gallery.update',
        'destroy' => 'admin.gallery.destroy',
    ]);
});
