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
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            // On utilise une énumération pour les types définis dans votre code
            $table->enum('type', ['urgent', 'information', 'evenement', 'avertissement', 'general'])->default('general');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable(); // Optionnel : pour faire disparaître l'annonce
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
