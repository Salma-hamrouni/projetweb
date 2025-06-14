<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEtapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 
    public function up()
{
    Schema::table('etapes', function (Blueprint $table) {
        $table->enum('status', ['en_cours', 'termine', 'abondonee'])->default('en_cours');
    });
}



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('etapes', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

}
