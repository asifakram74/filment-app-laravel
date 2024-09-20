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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('airlines');
            $table->string('price');
            $table->string('stock'); 
            $table->string('sector'); 
            $table->string('pnr'); 
            $table->string('dept_date')->nullable(); 
            $table->string('dept_day')->nullable(); 
            $table->string('dept_timing')->nullable();
            $table->string('des_arv_day')->nullable();
            $table->string('des_arv_date')->nullable(); 
            $table->string('des_arv_timing')->nullable(); 
            $table->string('arv_date')->nullable(); 
            $table->string('arv_day')->nullable(); 
            $table->string('arv_timing')->nullable();
            $table->string('return_back_day')->nullable();
            $table->string('return_back_date')->nullable(); 
            $table->string('return_back_timing')->nullable(); 
            $table->string('flight_number'); 
            $table->string('return_flight_number')->nullable(); 
            $table->string('category'); 
            $table->rememberToken();
             $table->timestamps();
        });
    }     

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
