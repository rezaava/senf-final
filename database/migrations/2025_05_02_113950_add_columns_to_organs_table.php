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
        Schema::table('organs', function (Blueprint $table) {
            $table->string('postalCode',10)->nullable();
            $table->date('RegistrationDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organs', function (Blueprint $table) {
            $table->dropColumn('postalCode');
            $table->dropColumn('RegistrationDate');
        });
    }
};
