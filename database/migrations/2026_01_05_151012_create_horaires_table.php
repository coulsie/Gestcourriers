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
        Schema::create('horaires', function (Blueprint $table) {
        $table->id();
        $table->string('jour'); // Lundi, Mardi, etc.
        $table->time('heure_debut'); // ex: 07:30:00
        $table->time('heure_fin');   // ex: 16:30:00
        $table->integer('tolerance_retard')->default(15); // minutes avant d'être marqué "En Retard"
        $table->boolean('est_ouvre')->default(true); // false pour Samedi/Dimanche
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaires');
    }
};
