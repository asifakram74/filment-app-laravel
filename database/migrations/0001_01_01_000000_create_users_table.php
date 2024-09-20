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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone'); 
            $table->string('password'); 
            $table->string('agency')->nullable();
            $table->enum('agency_category',['IATA','NON-IATA'])->default('NON-IATA');
            $table->string('agency_address')->nullable();
            $table->string('city')->nullable();
            $table->string('terms')->nullable();           
            $table->string('otp')->nullable();           
            $table->string('otp_expiry')->nullable();           
            $table->string('otp_count')->nullable();           
            $table->enum('role',['Admin','Manager','Agent'])->default('Agent');
            $table->enum('status',['Active','Block', 'Pending'])->default('Pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
             $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
