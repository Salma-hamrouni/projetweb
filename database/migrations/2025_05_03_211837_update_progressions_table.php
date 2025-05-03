<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgressionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('progressions', function (Blueprint $table) {
            // 1. D'abord modifier la colonne existante (si elle existe)
            if (Schema::hasColumn('progressions', 'progress')) {
                $table->decimal('pourcentage', 5, 2)
                      ->default(0)
                      ->after('etape_id')
                      ->change();
            } else {
                // 2. Ou créer la nouvelle colonne
                $table->decimal('pourcentage', 5, 2)
                      ->default(0)
                      ->after('etape_id');
            }
            
            // 3. Ajouter la colonne date
            $table->date('date')
                  ->nullable()  // Rend la colonne nullable
                  ->after('pourcentage');
            
            // 4. Supprimer les anciennes colonnes si elles existent
            if (Schema::hasColumn('progressions', 'progress')) {
                $table->dropColumn('progress');
            }
            if (Schema::hasColumn('progressions', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('progressions', function (Blueprint $table) {
            // Recréer les anciennes colonnes si nécessaire
            $table->integer('progress')->default(0);
            $table->string('status', 20)->default('en_cours');
            
            // Supprimer les nouvelles colonnes
            $table->dropColumn(['pourcentage', 'date']);
        });
    }
}