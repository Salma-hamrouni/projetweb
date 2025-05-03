<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

  
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo', // Ajoutez la photo ici
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relations
  // Dans le modèle User
public function objectifs()
{
    return $this->hasMany(Objectif::class);
}


    public function progressions()
    {
        return $this->hasMany(Progression::class);
    }
 // Ajoute cette méthode dans la classe User
public function etapes()
{
    return $this->hasMany(Etape::class);
}


public function journals()
{
    return $this->hasMany(Journal::class);
}

}
