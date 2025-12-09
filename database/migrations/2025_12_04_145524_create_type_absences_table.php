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
        Schema::create('type_absences', function (Blueprint $table) {

            $table->id(); // Colonne TypeAbsenceID (auto-incrément)
            $table->enum('nom_type', ['Congé','Repos Maladie','Mission','Permission','Autres'])->default('Congé'); // Define the enum column
            $table->string('code', 10)->nullable(); // Colonne Code
            $table->text('description')->nullable(); // Colonne Description
            $table->boolean('est_paye')->default(false); // Colonne EstPaye
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_absences');
    }
};
