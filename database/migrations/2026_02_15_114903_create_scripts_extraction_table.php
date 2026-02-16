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
    Schema::create('scripts_extraction', function (Blueprint $table) {
        $table->id();
        $table->string('nom'); // Nom du script (ex: "Extraction Mensuelle TVA")
        $table->text('description')->nullable();
        
        // Critères de filtrage spécifiques
        $table->string('type_entreprise')->nullable();
        $table->string('type_impot')->nullable();
        $table->string('type_contribuable')->nullable();
        
        // Période d'extraction
        $table->date('date_debut')->nullable();
        $table->date('date_fin')->nullable();
        
        // Stockage JSON pour les options avancées (ex: colonnes sélectionnées)
        $table->json('parametres')->nullable(); 
        
        $table->timestamps(); // created_at et updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scripts_extraction');
    }
};
