<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('objectif_id'); // Clé étrangère
            $table->string('name'); // Exemple de champ pour un nom
            $table->text('description')->nullable(); // Description de la location
            $table->timestamps();
            
            // Définir la clé étrangère
            $table->foreign('objectif_id')->references('id')->on('objectifs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
