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
    Schema::table('reponse_notifications', function (Blueprint $table) {
        // Ajoute un champ texte pour les commentaires, placé après le champ 'approuvee'
        $table->text('appreciation_du_superieur')->nullable()->after('approuvee');
    });
}

public function down()
{
    Schema::table('reponse_notifications', function (Blueprint $table) {
        $table->dropColumn('appreciation_du_superieur');
    });
}
};
