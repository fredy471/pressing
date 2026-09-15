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
        Schema::create('pending_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('payload');
            $table->decimal('total_amount', 10, 2);
            $table->string('fedapay_transaction_id')->nullable();
            $table->enum('status', ['en_attente', 'recue', 'en_lavage', 'terminee', 'livree', 'annulee'])->default('en_attente');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_orders');
    }
};
