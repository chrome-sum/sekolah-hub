<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Modules\CMS\Models\Post;
use App\Modules\Contact\Models\ContactMessage;
use App\Modules\PPDB\Models\Registration;
use App\Models\User;

// The public homepage root route '/' is handled by the Theme Module.

Route::get('/dashboard', function () {
    $newsCount = 0;
    $unreadContacts = 0;
    $newRegistrations = 0;
    $usersCount = 0;

    try {
        $newsCount = Post::where('status', 'published')->count();
    } catch (\Exception $e) {}

    try {
        $unreadContacts = ContactMessage::where('status', 'unread')->count();
    } catch (\Exception $e) {}

    try {
        $newRegistrations = Registration::where('status', 'submitted')->count();
    } catch (\Exception $e) {}

    try {
        $usersCount = User::count();
    } catch (\Exception $e) {}

    return view('dashboard', compact('newsCount', 'unreadContacts', 'newRegistrations', 'usersCount'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
