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
        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('operator_id')->nullable()->after('user_id');
            $table->tinyInteger('operator_contract_type')->nullable()->comment('1: حقوق ثابت - 2: اجاره صندلی - 3: درصدی')->after('operator_id');
            $table->decimal('operator_percent', 5, 2)->nullable()->after('operator_contract_type');
            $table->decimal('organ_percent', 5, 2)->nullable()->after('operator_percent');
            $table->decimal('app_percent', 5, 2)->nullable()->after('organ_percent');
            $table->decimal('operator_share', 15, 2)->nullable()->after('app_percent');
            $table->decimal('organ_share', 15, 2)->nullable()->after('operator_share');
            $table->decimal('app_share', 15, 2)->nullable()->after('organ_share');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'operator_id',
                'operator_contract_type',
                'operator_percent',
                'organ_percent',
                'app_percent',
                'operator_share',
                'organ_share',
                'app_share'
            ]);
        });
    }
};
