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
        Schema::table('imputations', function (Blueprint $table) {
            // Ajoute la colonne après 'courrier_id' et permet les valeurs NULL
            $table->string('chemin_fichier')->after('courrier_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('imputations', function (Blueprint $table) {
            // Supprime la colonne en cas de rollback
            $table->dropColumn('chemin_fichier');
        });
    }
};
