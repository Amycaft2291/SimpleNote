<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppearanceController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

// ─── Trang Welcome ────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Dashboard, Notes, Appearance & Labels (Cần Auth) ─────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard — hiển thị tất cả ghi chú
    Route::get('/dashboard', [NoteController::class, 'index'])->name('dashboard');

    // Notes CRUD (RESTful, AJAX)
    Route::post  ('/notes',              [NoteController::class, 'store'])->name('notes.store');
    Route::put   ('/notes/{note}',       [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}',       [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::patch ('/notes/{note}/pin',   [NoteController::class, 'togglePin'])->name('notes.pin');

    // Quản lý Giao diện (Appearance)
    Route::get  ('/settings/appearance', [AppearanceController::class, 'edit'])->name('appearance.edit');
    Route::patch('/settings/appearance', [AppearanceController::class, 'update'])->name('appearance.update');

    // Quản lý Nhãn (Labels)
    Route::post ('/labels',             [LabelController::class, 'store'])->name('labels.store');
    Route::put('/labels/{label}',       [LabelController::class, 'update'])->name('labels.update');
    Route::delete('/labels/{label}',    [LabelController::class, 'destroy'])->name('labels.destroy');

    // Profile
    Route::get   ('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch ('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //khóa gchu
    Route::post('/notes/{note}/unlock', [App\Http\Controllers\NoteController::class, 'unlock'])->name('notes.unlock');
});

// // ─── Kích hoạt tài khoản qua email ────────────────────────────────────────────
// Route::get('/activate/{token}', function ($token) {
//     $user = User::where('activation_token', $token)->first();

//     if ($user) {
//         $user->is_activated     = true;
//         $user->activation_token = null;
//         $user->save();

//         return redirect('/dashboard')->with('status', 'Tài khoản của bạn đã được kích hoạt thành công!');
//     }

//     return redirect('/dashboard')->withErrors([
//         'activation' => 'Link kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt.',
//     ]);
// })->name('activate');

require __DIR__ . '/auth.php';