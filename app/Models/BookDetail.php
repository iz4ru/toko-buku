<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookDetail extends Model
{
    use HasFactory;
    protected $table = 'book_details';
    protected $fillable = [
        'book_id',
        'stock',
        'price'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
