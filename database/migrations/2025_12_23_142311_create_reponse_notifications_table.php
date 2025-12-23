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
        Schema::create('reponse_notifications', function (Blueprint $table) {
            $table->id();
            // Clé étrangère vers la notification parente
            $table->foreignId('id_notification')->constrained('notifications_taches')->onDelete('cascade');
            
            // Clé étrangère vers l'agent qui répond
            $table->foreignId('agent_id')->constrained('users'); 
            
            $table->text('message'); // Le contenu de la réponse
            $table->string('Reponse_Piece_jointe')->nullable(); // Optionnel : chemin vers un fichier
            $table->timestamps(); // date_creation et date_modification
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponse_notifications');
    }
};
