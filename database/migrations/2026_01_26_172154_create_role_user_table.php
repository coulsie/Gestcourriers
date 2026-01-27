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
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            // Liaison vers la table users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Liaison vers la table roles
            $table->foreignId('role_id')->constrained()->onDelete('cascade');

            // Optionnel : ajoute des timestamps si vous voulez savoir quand le rôle a été attribué
            // $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
