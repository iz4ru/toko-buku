<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';
    protected $fillable = [
        'category_id',
        'book_code',
        'title',
        'publisher',
        'author',
        'publication_year',
        'book_cover'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function bookDetail()
    {
        return $this->hasOne(BookDetail::class, 'book_id');
    }
}
