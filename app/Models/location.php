<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    use HasFactory;

    protected $fillable = [
        'objectif_id',
        'latitude',
        'longitude',
        'address', 
    ];


    public function objectif()
    {
        return $this->belongsTo(Objectif::class);
    }
}
