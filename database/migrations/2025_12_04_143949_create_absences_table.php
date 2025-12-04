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
        Schema::create('absences', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->foreignId('agent_id')->constrained(); // Clé étrangère vers la table agents
            $table->foreignId('type_absence_id')->constrained(); // Clé étrangère vers la table types_absence
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('approuvee')->default(false); // Valeur par défaut à false
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
