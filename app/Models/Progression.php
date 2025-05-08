<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progression extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'etape_id', 'pourcentage', 'date'];

    public function etape()
    {
        return $this->belongsTo(Etape::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatutAttribute()
    {
        return $this->pourcentage >= 100 ? 'termine' : 'en_cours';
    }
}
