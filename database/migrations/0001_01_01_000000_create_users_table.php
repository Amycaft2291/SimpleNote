<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        //ttin ng dùng
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('display_name'); 
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            $table->string('note_password')->nullable();
            $table->integer('wrong_password_count')->default(0);
            $table->timestamp('locked_until')->nullable(); 

            //giao diện ng dùng
            $table->integer('font_size')->default(16);
            $table->string('note_color')->default('#ffffff'); 
            $table->string('theme')->default('light');
            
            //kích hoạt tk
            $table->boolean('is_activated')->default(false);
            $table->string('activation_token')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        //reset pass
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        //remember me -> giữ phiên đn
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