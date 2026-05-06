<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng ghi chú [cite: 181]
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_pinned')->default(false); // Tính năng ghim [cite: 28, 182]
            $table->timestamp('pinned_at')->nullable();
            $table->string('password')->nullable(); // Khóa ghi chú [cite: 39, 183]
            $table->timestamps();
        });

        // Bảng nhãn [cite: 183]
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Bảng trung gian Ghi chú - Nhãn [cite: 184]
        Schema::create('note_label', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->foreignId('label_id')->constrained()->onDelete('cascade');
        });

        // Bảng chia sẻ ghi chú [cite: 185]
        Schema::create('note_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->string('email'); // Email người nhận [cite: 44, 238]
            $table->enum('permission', ['read', 'edit'])->default('read'); // Quyền xem/sửa [cite: 45, 238]
            $table->timestamps();
        });

        // Bảng ảnh đính kèm [cite: 186]
        Schema::create('note_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
