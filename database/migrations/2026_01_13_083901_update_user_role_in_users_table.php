<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        // On modifie la colonne pour accepter les nouveaux rôles
        $table->string('role')->change();
    });
   // Optionnel : Mettre à jour les anciennes données
    DB::table('users')->where('role', 'admin')->update(['role' => 'directeur']);
    DB::table('users')->where('role', 'utilisateur')->update(['role' => 'agent']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
