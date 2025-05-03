<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            // Vérifier si la colonne 'completed_at' existe
            if (Schema::hasColumn('objectifs', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->change();
            } else {
                // Si la colonne n'existe pas, l'ajouter
                $table->timestamp('completed_at')->nullable();
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
        Schema::table('objectifs', function (Blueprint $table) {
            // Revert to non-nullable column
            if (Schema::hasColumn('objectifs', 'completed_at')) {
                $table->timestamp('completed_at')->nullable(false)->change();
            }
        });
    }
}
