<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol_perm extends Model
{
    use HasFactory;

    protected $fillable = [
        'rol_id',
        'perm_id'
    ];
}
