<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'deadline', 'user_id',
        'completed_at', 'lien', 'latitude', 'longitude', 'status', 'file_path'
    ]; 

    protected $casts = [
        'deadline' => 'datetime',  
        'completed_at' => 'datetime', 
        'latitude' => 'float',  
        'longitude' => 'float',  
    ];

    public function user()
    {
        return $this->belongsTo(User::class);  
    }

    public function etapes()
{
    return $this->hasMany(Etape::class);
}


    public function progressions()
    {
        return $this->hasMany(Progression::class);  
    }

    public function getProgressionAttribute()
    {
        $total = $this->etapes->count();  
        $terminees = $this->etapes->where('status', 'termine')->count();  
        return $total > 0 ? round(($terminees / $total) * 100, 2) : 0;  
    }
    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }
    
   
}
