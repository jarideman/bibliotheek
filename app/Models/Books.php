<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lent_books;

class Books extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'writer',
        'genre',
        'purchase_date'
    ];

    public function uitlenen(){
        return $this->hasMany(Lent_books::class, 'book_id');
    }
}
