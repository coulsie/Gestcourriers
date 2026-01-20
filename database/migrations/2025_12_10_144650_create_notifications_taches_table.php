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
        $table->id('id_notification');
        $table->unsignedBigInteger('id_agent');
        $table->string('titre');
        $table->text('description');

        // Gardez une seule définition pour date_creation
        $table->timestamp('date_creation')->useCurrent();

        $table->timestamp('date_echeance')->nullable();
        $table->string('suivi_par', 100);
        $table->enum('priorite', ['Faible', 'Moyenne', 'Élevée', 'Urgent'])->default('Moyenne');
        $table->enum('statut', ['Non lu', 'En cours', 'Complétée', 'Annulée'])->default('Non lu');
        $table->string('lien_action', 512)->nullable();
        $table->timestamp('date_lecture')->nullable();
        $table->timestamp('date_completion')->nullable();

        // Note: Si vous utilisez Laravel 12 (2026), vous pouvez aussi utiliser $table->timestamps();
        // Mais si vous préférez vos noms personnalisés, restez comme ceci.
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
