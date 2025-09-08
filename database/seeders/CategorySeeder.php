<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Fiction',
            'book_type' => 'Novel'
        ]);
        
        Category::create([
            'name' => 'Non-Fiction',
            'book_type' => 'Biography'
        ]);

        Category::create([
            'name' => 'Science',
            'book_type' => 'Educational'
        ]);

        Category::create([
            'name' => 'History',
            'book_type' => 'Documentary'
        ]);
    }
}
