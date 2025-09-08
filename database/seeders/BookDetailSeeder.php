<?php

namespace Database\Seeders;

use App\Models\BookDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookDetail::create([
            'book_id' => 1,
            'stock' => 10,
            'price' => 19.99,
        ]);
    }
}
