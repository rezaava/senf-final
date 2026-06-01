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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('organ_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('operator_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->bigInteger('price');
            $table->bigInteger('remain')->nullable();
            $table->text('description');
            $table->unsignedTinyInteger('status')->default(3)->comment('2:done 3:failed');
            $table->char('payment_id',32)->index()->nullable();
            $table->text('invoice_details')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('transaction_result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
