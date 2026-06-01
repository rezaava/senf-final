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
        Schema::create('reservation_financials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('min_paid_amount');
            $table->unsignedBigInteger('final_total_amount')->nullable();

            $table->unsignedBigInteger('salon_share')->nullable();
            $table->unsignedBigInteger('operator_share')->nullable();
            $table->unsignedBigInteger('platform_fee')->nullable();

            $table->foreignId('salon_contract_id')
                ->nullable()
                ->constrained('contracts');

            $table->foreignId('operator_contract_id')
                ->nullable()
                ->constrained('contracts');

            $table->timestamp('pricing_locked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_financials');
    }
};
