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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tracking_code')->unique();
            $table->string('statut')->default('en_attente');
            $table->string('pickup_phone', 10)->nullable();
            $table->string('pickup_adress')->nullable();
            $table->string('delivery_mode')->default('collecte_livraison');
            $table->decimal('total_amount', 10, 2)->default('0');
            $table->dateTime('deposit_deadline')->nullable();
            $table->dateTime('deposited_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
