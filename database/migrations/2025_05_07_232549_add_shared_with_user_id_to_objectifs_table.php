<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSharedWithUserIdToObjectifsTable extends Migration
{
    public function up()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            $table->unsignedBigInteger('shared_with_user_id')->nullable()->after('visibilite');
            $table->foreign('shared_with_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            $table->dropForeign(['shared_with_user_id']);
            $table->dropColumn('shared_with_user_id');
        });
    }
}
