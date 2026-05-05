<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('title')->nullable(); 
            $table->text('content')->nullable(); 

            $table->boolean('is_pinned')->default(false); 

            $table->string('password')->nullable(); 

            $table->string('color')->default('#ffffff'); 

            $table->enum('view_mode', ['list', 'small_icon', 'large_icon'])->default('list');

            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
