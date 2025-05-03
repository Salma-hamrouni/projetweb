<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTitreToTitleInObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            // Vérifie si la colonne 'titre' existe avant de la renommer
            if (Schema::hasColumn('objectifs', 'titre')) {
                $table->renameColumn('titre', 'title');
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
            // Vérifie si la colonne 'title' existe avant de la renommer
            if (Schema::hasColumn('objectifs', 'title')) {
                $table->renameColumn('title', 'titre');
            }
        });
    }
}
