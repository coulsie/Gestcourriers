<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('reponse_notifications', function (Blueprint $table) {
        // Option 1 : Enum (Strict)
        $table->enum('approuvee', ['en_attente', 'acceptee', 'rejetee'])->default('en_attente')->after('id');

        // Option 2 : String (Plus flexible pour le futur)
        // $table->string('approuvee')->default('en_attente')->after('id');
    });
}

public function down()
{
    Schema::table('reponse_notifications', function (Blueprint $table) {
        $table->dropColumn('approuvee');
    });
}
};
