<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();

            // Lien vers l'imputation concernée
            $table->foreignId('imputation_id')->constrained()->onDelete('cascade');

            // L'agent qui rédige la réponse
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');

            // Contenu de la réponse
            $table->text('contenu');

            // Stockage des fichiers de preuve (JSON pour plusieurs fichiers)
            $table->text('fichiers_joints')->nullable();

            // Date effective de la réponse
            $table->dateTime('date_reponse')->default(now());

            // État d'avancement déclaré par l'agent (en %)
            $table->integer('pourcentage_avancement')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reponses');
    }
};
