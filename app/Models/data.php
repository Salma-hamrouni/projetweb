<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',       
        'objectif_id',   
        'name',          
        'path',          
        'type',          
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function objectif()
    {
        return $this->belongsTo(Objectif::class);
    }
}
