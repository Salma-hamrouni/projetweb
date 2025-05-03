<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // Ajoute ceci

class Etape extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'date_limite',
        'objectif_id',
        'user_id',
        'status'
    ];


    public function objectif()
    {
        return $this->belongsTo(Objectif::class, 'objectif_id');
    }

    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }
    
   
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
