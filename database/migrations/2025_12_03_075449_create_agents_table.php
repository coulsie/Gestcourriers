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
       Schema::create('agents', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée

            // Informations personnelles
            $table->string('matricule')->unique(); // Numéro matricule unique de l'agent
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('status', ['Agent','Chef de service','Sous-directeur','Directeur','Conseiller Technique','Conseiller Spécial'])->default('Agent'); // Define the enum column
            $table->enum('sexe', ['Male', 'Female'])->nullable(); // Adjust 'name' if needed for column order
            $table->date('date_of_birth')->nullable();
            $table->string('place_birth')->nullable();
            
            $table->string('Photo');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('Emploi')->nullable(); // Poste ou fonction de l'agent
            $table->string('Grade')->nullable();
            $table->date('Date_Prise_de_service');
            $table->string('Personne_a_prevenir')->nullable();
            $table->string('Contact_personne_a_prevenir')->nullable();

            // Clé étrangère vers la table 'services' (relation Agent appartient à un Service)
            $table->foreignId('service_id')
                  ->constrained('services') // Assure l'intégrité référentielle
                  ->onDelete('restrict');  // Empêche la suppression d'un service s'il a encore des agents

            // Lien optionnel vers la table 'users' de Laravel pour l'authentification
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null'); // Si le compte utilisateur est supprimé, l'agent reste

            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
