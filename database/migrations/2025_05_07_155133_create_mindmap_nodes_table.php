<?php

// database/migrations/xxxx_xx_xx_create_mindmap_nodes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMindmapNodesTable extends Migration
{
    public function up()
    {
        Schema::create('mindmap_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('mindmap_nodes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mindmap_nodes');
    }
}
