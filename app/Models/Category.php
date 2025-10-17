<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name'];

    public function book()
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    public function bookTypes()
    {
        return $this->hasMany(BookType::class, 'category_id');
    }
}
