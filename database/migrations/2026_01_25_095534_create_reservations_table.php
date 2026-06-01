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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();               // مشتری
            $table->foreignId('operator_id')->constrained('users');    // آرایشگر
            $table->foreignId('organ_id')->constrained();              // سالن

            $table->dateTime('start_at');
            $table->dateTime('end_at');

            $table->enum('status', [
                'pending',
                'paid',
                'canceled',
                'done',
            ])->default('pending');

            $table->enum('pricing_status', [
                'fixed',
                'waiting_operator_price',
                'min_paid_waiting_service',
                'finalized',
            ])->default('fixed');

            $table->unsignedBigInteger('total_price')->default(0);

            $table->timestamps();

            $table->index(['operator_id', 'start_at', 'end_at']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
