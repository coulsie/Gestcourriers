<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('notifications_taches', function (Blueprint $table) {
            // Ajoute une colonne 'document' qui peut être nulle (facultative)
            $table->string('document', 512)->nullable()->after('lien_action');
        });
    }

    public function down(): void
    {
        Schema::table('notifications_taches', function (Blueprint $table) {
            // Supprime la colonne si on annule la migration
            $table->dropColumn('document');
        });
    }
};
