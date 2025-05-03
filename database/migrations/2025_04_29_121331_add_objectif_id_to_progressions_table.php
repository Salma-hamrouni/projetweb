<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjectifIdToProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('progressions', function (Blueprint $table) {
            // Vérifie si la colonne 'objectif_id' existe avant de l'ajouter
            if (!Schema::hasColumn('progressions', 'objectif_id')) {
                $table->foreignId('objectif_id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('progressions', function (Blueprint $table) {
            // Vérifie si la colonne 'objectif_id' existe avant de la supprimer
            if (Schema::hasColumn('progressions', 'objectif_id')) {
                $table->dropForeign(['objectif_id']);
                $table->dropColumn('objectif_id');
            }
        });
    }
}
