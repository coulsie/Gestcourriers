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
            // Ajoute la colonne booléenne, par défaut à 'false' (0)
            $table->boolean('affecter')->default(false)->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('courriers', function (Blueprint $table) {
            $table->dropColumn('affecter');
        });
    }
};
