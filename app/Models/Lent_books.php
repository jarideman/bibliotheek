<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Books;

class Lent_books extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'lent_date',
        'return_date',
        'times_extended'
    ];

    public function book(){
        return $this->belongsTo(Books::class);
    }
}
