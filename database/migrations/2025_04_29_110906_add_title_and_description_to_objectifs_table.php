<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleAndDescriptionToObjectifsTable extends Migration
{
    /**
     * Appliquer la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            // Vérifie si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('objectifs', 'title')) {
                $table->string('title');
            }

            if (!Schema::hasColumn('objectifs', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    /**
     * Revenir sur la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            // Vérifie si les colonnes existent avant de les supprimer
            if (Schema::hasColumn('objectifs', 'title')) {
                $table->dropColumn('title');
            }

            if (Schema::hasColumn('objectifs', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
}
