<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToObjectifsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            $table->string('status')->default('en_cours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}