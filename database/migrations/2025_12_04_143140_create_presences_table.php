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
        Schema::create('presences', function (Blueprint $table) {
            $table->id(); // Utilise BigIncrements, par défaut une clé primaire auto-incrémentée
            $table->unsignedBigInteger('agent_id'); // Le type de colonne pour la clé étrangère
            $table->timestamp('heure_arrivee');
            $table->timestamp('heure_depart')->nullable(); // Rend la colonne facultative
            $table->string('statut', 50);
            $table->enum('status', ['Absent','Présent','En Retard'])->default('Présent'); // Define the enum column
            $table->text('notes')->nullable(); // Rend la colonne facultative
            // Déclaration de la clé étrangère
            $table->foreign('agent_id')->references('id')->on('agents');
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
