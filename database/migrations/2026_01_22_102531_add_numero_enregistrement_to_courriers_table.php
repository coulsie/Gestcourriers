<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('courriers', function (Blueprint $table) {
            // Ajoute la colonne après l'ID. On la met en 'nullable' au début
            // pour ne pas bloquer les anciens enregistrements de 2026.
            $table->string('num_enregistrement')->after('id')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('courriers', function (Blueprint $table) {
            $table->dropColumn('num_enregistrement');
        });
    }
};
