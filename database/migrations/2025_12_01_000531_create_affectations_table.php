<?php


// database/migrations/..._create_affectations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffectationsTable extends Migration
{
    public function up()
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            
            // Liens vers les tables courriers et users
            $table->foreignId('courrier_id')->constrained('courriers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('statut')->default('pending'); // pending, in_progress, completed, rejected, etc.
            $table->text('commentaires')->nullable();
            $table->timestamp('date_affectation')->useCurrent(); // Date de début du traitement
            $table->timestamp('date_traitement')->nullable(); // Date de fin du traitement

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affectations');
    }
}
