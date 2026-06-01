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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('status')->default(0)->comment('0:wating 1:confirm 2:rad');
            $table->string('description');
            $table->string('reject')->nullable();
            $table->unsignedBigInteger('requestable_id')->comment('model id')->nullable();
            $table->string('requestable_type')->comment('model type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
