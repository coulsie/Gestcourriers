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
        Schema::create('courriers', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            //$table->enum('type', ['Entrant', 'Sortant', 'Other_Value']);
            $table->string('type', 50)->change();
            $table->string('type', 255)->change();
            $table->string('objet',255);
            $table->text('description')->nullable();
            $table->date('date_courrier');
            $table->string('expediteur_nom');
            $table->string('expediteur_contact')->nullable();
            $table->string('destinataire_nom');
            $table->string('destinataire_contact')->nullable();
            $table->string('statut')->default('pending');
            $table->string('assigne_a')->nullable();
            $table->string('chemin_fichier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courriers');
    }
};
