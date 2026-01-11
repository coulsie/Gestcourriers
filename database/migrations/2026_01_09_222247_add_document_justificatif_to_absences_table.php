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
    Schema::table('absences', function (Blueprint $table) {
        // nullable car une absence n'a pas de justificatif au moment de sa création
        $table->string('document_justificatif')->nullable()->after('Approuvee');
    });
}

public function down(): void
{
    Schema::table('absences', function (Blueprint $table) {
        $table->dropColumn('document_justificatif');
    });
}
};
