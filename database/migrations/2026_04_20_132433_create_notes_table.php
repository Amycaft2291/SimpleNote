<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id(); // tạo cột 'id' là primary key
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('created_by')
                ->constrained('users') // tham chiếu bảng users
                ->cascadeOnDelete();   // user bị xóa → note bị xóa theo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
