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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['mobile_money', 'carte', 'paiement_livraison']);
            $table->string('provider')->default('fedapay');
            $table->enum('status', ['en_attente', 'reussi', 'echoue', 'rembourse'])->default('en_attente');
            $table->string('provider_reference')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
