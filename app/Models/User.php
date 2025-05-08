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
        'photo', 
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


public function objectifs()
{
    return $this->hasMany(Objectif::class);
}


 
public function etapes()
{
    return $this->hasMany(Etape::class);
}


public function journals()
{
    return $this->hasMany(Journal::class);
}
public function objectifsPartagesAvecMoi()
{
    return $this->hasMany(Objectif::class, 'shared_with_user_id');
}

}
