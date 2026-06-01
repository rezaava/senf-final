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
        Schema::table('transactions', function (Blueprint $table) {
            $table->tinyInteger('transaction_type')->nullable()->comment('1: پرداخت مشتری - 2: سهم آرایشگر - 3: سهم سالن - 4: سهم اپ - 5: اجاره صندلی - 6: حقوق آرایشگر')->after('status');
            $table->unsignedBigInteger('wallet_user_id')->nullable()->after('transaction_type')->comment('شناسه کیف پول متعلق به');
            $table->tinyInteger('wallet_type')->nullable()->comment('1: اپ - 2: سالن - 3: آرایشگر')->after('wallet_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_type',
                'wallet_user_id',
                'wallet_type'
            ]);
        });
    }
};
