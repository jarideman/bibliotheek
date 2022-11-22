<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'message'
    ];

    public function rol(){
        return $this->belongsTo(Rols::class);
    }
}
