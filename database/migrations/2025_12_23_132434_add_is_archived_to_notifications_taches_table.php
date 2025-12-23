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
       Schema::table('notifications_taches', function (Blueprint $table) {
        $table->boolean('is_archived')->default(false); // false = active, true = archivée
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications_taches', function (Blueprint $table) {
            //
        });
    }
};
