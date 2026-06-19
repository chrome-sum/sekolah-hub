<?php

declare(strict_types=1);

use App\Modules\Theme\Http\Controllers\Admin\ThemeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin/themes')->name('admin.themes.')->group(function () {
    Route::get('/', [ThemeController::class, 'index'])->name('index');
    Route::post('/activate', [ThemeController::class, 'activate'])->name('activate');
    Route::post('/sections', [ThemeController::class, 'updateSections'])->name('sections.update');
    Route::post('/settings', [ThemeController::class, 'updateSettings'])->name('settings.update');
});
