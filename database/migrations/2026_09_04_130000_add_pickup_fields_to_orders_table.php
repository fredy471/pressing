<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'pickup_phone')) {
                $table->string('pickup_phone', 10)->nullable()->after('statut');
            }

            if (! Schema::hasColumn('orders', 'pickup_adress')) {
                $table->string('pickup_adress')->nullable()->after('pickup_phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'pickup_adress')) {
                $table->dropColumn('pickup_adress');
            }

            if (Schema::hasColumn('orders', 'pickup_phone')) {
                $table->dropColumn('pickup_phone');
            }
        });
    }
};
