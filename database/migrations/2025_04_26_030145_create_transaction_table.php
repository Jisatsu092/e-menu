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
            $table->string('code')->unique();
            $table->string('bowl_size')->nullable();
            $table->string('spiciness_level');
            $table->integer('total_price');
            $table->string('payment_proof')->nullable();
            $table->foreignId('payment_provider_id')->nullable()->constrained('payment_providers')->nullOnDelete();
            $table->enum('status', ['pending', 'proses', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
