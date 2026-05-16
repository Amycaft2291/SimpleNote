<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [NoteController::class, 'index'])->name('dashboard');

    //Setting
    Route::get  ('/settings/profile', [ProfileController::class, 'edit'])
        ->name('settings.profile');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])
        ->name('settings.profile.update');
 
    Route::get  ('/settings/appearance', [AppearanceController::class, 'edit'])
        ->name('settings.appearance');
    Route::patch('/settings/appearance', [AppearanceController::class, 'update'])
        ->name('settings.appearance.update');
 
    Route::get  ('/settings/security', [SecurityController::class, 'index'])
        ->name('settings.security');
    Route::patch('/settings/password', [SecurityController::class, 'updatePassword'])
        ->name('settings.password.update');

    // Notes CRUD (RESTful, AJAX)
    Route::post  ('/notes',              [NoteController::class, 'store'])->name('notes.store');
    Route::put   ('/notes/{note}',       [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}',       [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::patch ('/notes/{note}/pin',   [NoteController::class, 'togglePin'])->name('notes.pin');

    // // Quản lý Giao diện (Appearance)
    // Route::get  ('/settings/appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
    // Route::patch('/settings/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::delete('/note-images/{image}', [NoteController::class, 'deleteImage'])->name('notes.delete-image');

    Route::post('/notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.toggle-pin');
    Route::post('/notes/{note}/lock', [NoteController::class, 'lock'])->name('notes.lock');
    Route::post('/notes/{note}/unlock-temp', [NoteController::class, 'unlockTemp'])->name('notes.unlock-temp');
    Route::post('/notes/{note}/remove-pw', [NoteController::class, 'removePw'])->name('notes.remove-pw');

    Route::post('/labels', [LabelController::class, 'store'])->name('labels.store');
    Route::put('/labels/{label}', [LabelController::class, 'update'])->name('labels.update');
    Route::delete('/labels/{label}', [LabelController::class, 'destroy'])->name('labels.destroy');

    Route::get('/settings/appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
    Route::patch('/settings/appearance', [AppearanceController::class, 'update'])->name('appearance.update');
    Route::post('/user/set-note-password', [NoteController::class, 'setNotePassword'])->name('user.set-note-password');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('settings.profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';