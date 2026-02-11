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
    Schema::table('imputations', function (Blueprint $table) {
        // Ajout d'une clé étrangère vers la table users
        $table->foreignId('suivi_par')->nullable()->after('user_id')
              ->constrained('users')
              ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('imputations', function (Blueprint $table) {
        $table->dropForeign(['suivi_par']);
        $table->dropColumn('suivi_par');
    });
}

};
