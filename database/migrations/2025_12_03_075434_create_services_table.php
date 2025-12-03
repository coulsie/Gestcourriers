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
       Schema::create('services', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du service (ex: Service Informatique)
            $table->string('code', 10)->unique()->nullable(); // Code court (ex: SI)
            $table->text('description')->nullable(); // Description du service

            // Clé étrangère vers la table 'directions'
            $table->foreignId('direction_id')
                  ->constrained('directions') // Assure la contrainte d'intégrité référentielle
                  ->onDelete('cascade');     // Si une direction est supprimée, ses services le sont aussi

            $table->foreignId('head_id')->nullable()->constrained('agents')->onDelete('set null'); // Responsable du service (lien vers agents)

            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
