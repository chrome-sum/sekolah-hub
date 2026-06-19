<?php

declare(strict_types=1);

use App\Modules\PPDB\Http\Controllers\Admin\AcademicYearController;
use App\Modules\PPDB\Http\Controllers\Admin\AdmissionTrackController;
use App\Modules\PPDB\Http\Controllers\Admin\FormFieldController;
use App\Modules\PPDB\Http\Controllers\Admin\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('admin/ppdb')->name('admin.ppdb.')->group(function () {
    // Academic Years
    Route::resource('academic-years', AcademicYearController::class)->except(['show']);

    // Admission Tracks
    Route::resource('tracks', AdmissionTrackController::class)->except(['show']);

    // Form Fields per Track
    Route::post('tracks/{track}/form-fields/reorder', [FormFieldController::class, 'reorder'])->name('tracks.form-fields.reorder');
    Route::resource('tracks.form-fields', FormFieldController::class)->except(['show']);

    // Registrations
    Route::get('registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('registrations/export', [RegistrationController::class, 'export'])->name('registrations.export');
    Route::get('registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::put('registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.update_status');
    Route::put('documents/{document}/verify', [RegistrationController::class, 'verifyDocument'])->name('documents.verify');
    Route::get('documents/{document}/download', [RegistrationController::class, 'downloadDocument'])->name('documents.download');
});
