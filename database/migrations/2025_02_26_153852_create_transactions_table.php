<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
// File: create_transactions_table.php
public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('table_id')->constrained()->onDelete('cascade');
        $table->string('bowl_size');
        $table->string('spiciness_level');
        $table->integer('total_price');
        $table->string('payment_proof')->nullable();
        
        // Perbaikan foreign key
        $table->unsignedBigInteger('payment_provider_id')->nullable();
        $table->foreign('payment_provider_id')
            ->references('id')
            ->on('payment_providers')
            ->onDelete('SET NULL')
            ->onUpdate('CASCADE');
            
        $table->enum('status', ['pending', 'paid'])->default('pending');
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }

    
};
