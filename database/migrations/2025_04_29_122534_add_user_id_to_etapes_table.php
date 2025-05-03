<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('etapes', function (Blueprint $table) {
            // Vérifie si la colonne 'user_id' existe avant de l'ajouter
            if (!Schema::hasColumn('etapes', 'user_id')) {
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('etapes', function (Blueprint $table) {
            // Vérifie si la colonne 'user_id' existe avant de la supprimer
            if (Schema::hasColumn('etapes', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
