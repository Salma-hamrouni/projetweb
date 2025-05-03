<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;

    // Attributs par défaut
    protected $attributes = [
        'completed_at' => null,
    ];

    // Champs remplissables
    protected $fillable = [
        'title',
        'description',
        'date_limite',
        'user_id',
        'completed_at',
        'lien',
        'latitude',
        'longitude',
        'status', // ✅ Ajouter le champ status ici
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function etapes()
    {
        return $this->hasMany(Etape::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function data()
    {
        return $this->hasMany(Data::class);
    }

    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }
    private function calculateObjectifProgression(Objectif $objectif)
{
    $totalSteps = $objectif->etapes->count();
    
    if ($totalSteps === 0) {
        return 0;
    }

    $completedSteps = $objectif->etapes->filter(function($etape) {
        return $etape->progressions()->where('progress', 100)->exists();
    })->count();

    return ($completedSteps / $totalSteps) * 100;
}
  
}
