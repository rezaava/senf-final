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
        Schema::table('services', function (Blueprint $table) {
            $table->tinyInteger('off_type')->nullable()->comment('1: درصدی، 2: مبلغ ثابت')->after('off');
            $table->bigInteger('off_price', )->nullable()->after('price');
            $table->string('image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('off_type');
            $table->dropColumn('off_price');
            $table->dropColumn('image');
        });
    }
};
