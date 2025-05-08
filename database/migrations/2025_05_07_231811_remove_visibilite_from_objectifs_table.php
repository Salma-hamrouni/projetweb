<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveVisibiliteFromObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ajouter la colonne 'shared_with_user_id'
        Schema::table('objectifs', function (Blueprint $table) {
            $table->unsignedBigInteger('shared_with_user_id')->nullable()->after('status');  // Remplacer 'status' par la colonne après laquelle tu veux ajouter
            $table->foreign('shared_with_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprimer la colonne 'shared_with_user_id' et la contrainte
        Schema::table('objectifs', function (Blueprint $table) {
            $table->dropForeign(['shared_with_user_id']);
            $table->dropColumn('shared_with_user_id');
        });
    }
}
