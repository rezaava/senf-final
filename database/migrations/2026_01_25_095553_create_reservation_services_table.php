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
        Schema::create('reservation_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('service_id')->constrained();

            $table->unsignedInteger('duration_minutes');

            $table->unsignedBigInteger('base_price');        // حداقل یا قیمت پایه
            $table->unsignedBigInteger('final_price')        // قیمت نهایی
                ->nullable();

            $table->enum('pricing_mode', [
                'fixed',
                'ranged_min',
                'ranged_final',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_services');
    }
};
