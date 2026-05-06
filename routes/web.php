<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/activate/{token}', function ($token) {
    // Tìm user có token khớp với link
    $user = User::where('activation_token', $token)->first();

    if ($user) {
        // Cập nhật trạng thái kích hoạt và xóa token
        $user->is_activated = true;
        $user->activation_token = null;
        $user->save();
        
        // Chuyển hướng về trang chủ cùng thông báo thành công
        return redirect('/dashboard')->with('status', 'Tài khoản của bạn đã được kích hoạt thành công!');
    }

    // Nếu token sai hoặc đã được sử dụng
    return redirect('/dashboard')->withErrors(['activation' => 'Link kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt.']);
});

require __DIR__.'/auth.php';
