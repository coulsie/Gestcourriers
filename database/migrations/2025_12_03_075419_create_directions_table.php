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
       Schema::create('directions', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom complet de la direction (ex: Direction Générale)
            $table->string('code', 10)->unique()->nullable(); // Code court/identifiant unique (ex: DG, DAF)
            $table->text('description')->nullable(); // Description ou mission de la direction
            $table->foreignId('head_id')->nullable()->constrained('agents')->onDelete('set null'); // Clé étrangère pour le responsable (lien vers la table agents)
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directions');
    }
};
