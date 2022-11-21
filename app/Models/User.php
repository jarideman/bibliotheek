<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Rols;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'middlename',
        'surname',
        'foto',
        'email',
        'password',
        'adres',
        'postcode',
        'city',
        'rol_id',
    ];

    public function subscription(){
        return $this->hasMany(Subscription::class, 'id');
    }

    public function rol(){
        return $this->belongsTo(Rols::class);
    }

    public function abbonement(){
        return $this->belongsTo(Subscription::class);
    }
}
