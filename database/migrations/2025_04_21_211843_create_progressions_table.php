<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progressions', function (Blueprint $table) {
            $table->id();
            $table->boolean('terminee')->default(false);
            $table->text('commentaire')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // appartient à un utilisateur
            $table->foreignId('etape_id')->constrained()->onDelete('cascade'); // appartient à une étape
            $table->foreignId('objectif_id')->constrained()->onDelete('cascade'); // appartient à un objectif

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progressions');
    }
}
