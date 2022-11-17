<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Books;
use App\Models\Lent_books;

class Reservations extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'reservation_date'
    ];

    public function book(){
        return $this->belongsTo(Books::class);
    }

}
