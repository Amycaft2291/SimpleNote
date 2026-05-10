<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'is_activated',
                'activation_token',
                'font_size',
                'note_color',
                'theme',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->boolean('is_activated')->default(false);

            $table->string('activation_token')->nullable();

            $table->string('font_size')->nullable();

            $table->string('note_color')->nullable();

            $table->string('theme')->nullable();

        });
    }
};