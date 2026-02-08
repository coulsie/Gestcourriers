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
    Schema::table('courriers', function (Blueprint $table) {
        // On l'ajoute après le champ 'date_courrier' pour garder une structure logique
        $table->date('date_document_original')->nullable()->after('date_courrier');
    });
}

public function down(): void
{
    Schema::table('courriers', function (Blueprint $table) {
        $table->dropColumn('date_document_original');
    });
}

};
