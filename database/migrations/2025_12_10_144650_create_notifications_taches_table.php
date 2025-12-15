<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_taches', function (Blueprint $table) {

            // id_notification (INT Clé Primaire)
            $table->id('id_notification'); // Laravel utilise 'id()' pour créer une PK auto-incrémentée nommée id_notification

            // id_agent_assigne (INT Clé Étrangère)
            // Si vous avez une table 'users' ou 'agents', vous pouvez ajouter la contrainte de clé étrangère
            $table->foreignId('id_agent')
                  ->constrained('agents') // Remplacez 'users' par le nom de votre table d'agents si nécessaire
                  ->onDelete('cascade');

            // titre (VARCHAR(255))
            $table->string('titre', 255);

            // description (TEXT)
            $table->text('description');

            // date_creation (DATETIME)
            // Laravel inclut timestamps() par défaut, mais nous pouvons être explicites :
            $table->timestamp('date_creation')->useCurrent();

            // date_echeance (DATETIME nullable)
            $table->timestamp('date_creation')->nullable();
            $table->timestamp('date_echeance')->nullable();

            $table->string('suivi_par', 100);
            // priorite (ENUM)
            $table->enum('priorite', ['Faible', 'Moyenne', 'Élevée', 'Urgent'])->default('Moyenne');

            // statut (ENUM)
            $table->enum('statut', ['Non lu', 'En cours', 'Complétée', 'Annulée'])->default('Non lu');

            // lien_action (VARCHAR(512) nullable)
            $table->string('lien_action', 512)->nullable();

            // date_lecture (DATETIME nullable)
            $table->timestamp('date_lecture')->nullable();

            // date_completion (DATETIME nullable)
            $table->timestamp('date_completion')->nullable();

            // Optionnel: Laravel gère 'created_at' et 'updated_at' par défaut avec $table->timestamps();
            // Comme nous avons déjà date_creation et d'autres dates spécifiques,
            // nous n'ajoutons pas la ligne $table->timestamps(); si vous ne voulez pas de la colonne updated_at.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications_taches');
    }
}
