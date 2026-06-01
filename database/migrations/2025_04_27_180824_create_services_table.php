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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->unsignedBigInteger('organ_id');
            // $table->foreign('organ_id')->references('id')->on('organs')
            // ->onDelete('cascade')->onUpdate('cascade');
            $table->bigInteger('price')->unsigned();
            $table->integer('off')->default(0);
            $table->string('description');
            $table->string('soled')->default("0");
            $table->string('comment_counts')->default("0");
            $table->string("score")->default("0");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
