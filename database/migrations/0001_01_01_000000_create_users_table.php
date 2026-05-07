<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // Thay username bằng display_name
            $table->string('display_name'); 
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Để lưu dạng bcrypt [cite: 40]
            
            // Quản lý kích hoạt tài khoản [cite: 42, 213]
            $table->boolean('is_activated')->default(false);
            $table->string('activation_token')->nullable();
            
            // User Preferences (Tùy chỉnh người dùng) [cite: 45, 213]
            $table->integer('font_size')->default(14);
            $table->string('note_color')->default('#ffffff');
            $table->enum('theme', ['light', 'dark'])->default('light');
            
            $table->rememberToken();
            $table->timestamps();
        });
        // 2. Bảng Password Reset [cite: 61]
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Bảng Sessions để duy trì đăng nhập
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};