<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('notifications_taches', function (Blueprint $table) {
            // Renomme l'ancienne colonne en nouvelle colonne
            $table->renameColumn('id_agent', 'agent_id');
        });
    }

    public function down(): void
    {
        Schema::table('notifications_taches', function (Blueprint $table) {
            // Si vous annulez (rollback) la migration, renommez-la à l'original
            $table->renameColumn('agent_id', 'id_agent');
        });
    }
};
