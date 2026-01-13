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
            Schema::create('imputations', function (Blueprint $table) {
                $table->id();
                // Lien avec le courrier (document associé)
                $table->foreignId('courrier_id')->constrained()->onDelete('cascade');

                // L'utilisateur qui fait l'imputation
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                // Détermination automatique du niveau
                $table->enum('niveau', ['primaire', 'secondaire', 'tertiaire']);
                $table->text('instructions')->nullable();
                $table->text('observations')->nullable();
                $table->text('documents_annexes')->nullable(); // Chemins des fichiers annexes
                $table->date('date_imputation')->default(now());
                $table->date('date_traitement')->nullable(); // Date de traitement effective
                $table->date('echeancier')->nullable(); // Date limite de traitement
                $table->enum('statut', ['en_attente', 'en_cours', 'termine'])->default('en_attente');
                $table->timestamps();
            });

            // Table pivot pour assigner à un ou plusieurs agents
            Schema::create('agent_imputation', function (Blueprint $table) {
                $table->id();
                $table->foreignId('imputation_id')->constrained()->onDelete('cascade');
                $table->foreignId('agent_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imputations');
    }
};
