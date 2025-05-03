<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            // Si tu veux juste modifier la colonne 'description', utilise 'change'
            $table->string('title')->nullable(false)->change(); // Modification de la colonne 'title'
            $table->text('description')->nullable()->change(); // Modification de la colonne 'description'
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
            // Si tu veux rétablir les colonnes à leur état d'origine
            $table->string('title')->nullable(true)->change();
            $table->string('description')->nullable(true)->change();
        });
    }
}
