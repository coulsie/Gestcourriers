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
        Schema::table('reponses', function (Blueprint $table) {
            // Ajout des nouveaux champs
            $table->enum('validation', ['en_attente', 'acceptee', 'rejetee'])->default('en_attente')->after('id');
            $table->string('document_final_signe')->nullable()->after('validation');
            $table->timestamp('date_approbation')->nullable()->after('document_final_signe');
        });
    }

    public function down(): void
    {
        Schema::table('reponses', function (Blueprint $table) {
            // Suppression des champs si on annule la migration
            $table->dropColumn(['validation', 'document_final_signe', 'date_approbation']);
        });
    }
};
