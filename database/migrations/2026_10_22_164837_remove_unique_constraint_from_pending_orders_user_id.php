
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pending_orders', function (Blueprint $table) {
            // Ajouter un index non unique pour conserver
            // l'index nécessaire à la clé étrangère.
            $table->index('user_id', 'pending_orders_user_id_index');
        });

        Schema::table('pending_orders', function (Blueprint $table) {
            // Supprimer uniquement l'unicité de user_id.
            $table->dropUnique('pending_orders_user_id_unique');
        });
    }

    public function down(): void
    {
        // Le rollback rétablit l'unicité.
        Schema::table('pending_orders', function (Blueprint $table) {
            $table->unique('user_id', 'pending_orders_user_id_unique');
        });

        // Puis retire l'index non unique ajouté par up().
        Schema::table('pending_orders', function (Blueprint $table) {
            $table->dropIndex('pending_orders_user_id_index');
        });
    }
};
