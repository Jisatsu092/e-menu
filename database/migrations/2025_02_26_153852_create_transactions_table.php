<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('table_id')
                ->constrained('tables')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                $table->string('bowl_size');
                $table->string('spiciness_level');
                $table->integer('total_price');
                $table->string('payment_proof')->nullable();
                $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }

    
};
