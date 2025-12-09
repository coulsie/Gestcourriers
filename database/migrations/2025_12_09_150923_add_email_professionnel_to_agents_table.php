<?php

// database/migrations/YYYY_MM_DD_HHMMSS_add_email_professionnel_to_agents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Ajoute une colonne string 'email_professionnel', nullable, après 'nom_de_colonne_existante' (optionnel)
            $table->string('email_professionnel')->nullable()->unique()->after('id'); // Ajustez 'after' selon votre structure
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('email_professionnel');
        });
    }
};
