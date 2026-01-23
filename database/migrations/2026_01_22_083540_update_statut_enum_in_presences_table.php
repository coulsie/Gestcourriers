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
    public function up()
{
    // SQL brut car Laravel ne modifie pas nativement les ENUM via Blueprint
    DB::statement("ALTER TABLE presences MODIFY COLUMN statut ENUM('Absent', 'Présent', 'En Retard', 'Absence Justifiée') NOT NULL DEFAULT 'Présent'");
}

public function down()
{
    DB::statement("ALTER TABLE presences MODIFY COLUMN statut ENUM('Absent', 'Présent', 'En Retard') NOT NULL DEFAULT 'Présent'");
}
};
