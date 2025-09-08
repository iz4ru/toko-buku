<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::create([
        'category_id' => 1,
        'book_code' => 'BK001',
        'title' => 'The Great Gatsby',
        'publisher' => 'Scribner',
        'author' => 'F. Scott Fitzgerald',
        'publication_year' => 1925,
        'book_cover' => 'great_gatsby.jpg'
        ]);
    }
}
