<?php

namespace Database\Seeders;

use App\Models\BookType;
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
        $categories = [
            'Fiksi' => ['Novel', 'Cerpen', 'Drama'],
            'Non-Fiksi' => ['Sejarah', 'Self-Help', 'Ekonomi'],
            'Komik' => ['Manga', 'Komik Barat', 'Webtoon'],
            'Ilmiah' => ['Fisika', 'Kimia', 'Biologi'],
            'Biografi' => ['Otobiografi', 'Memoar'],
            'Anak-anak' => ['Dongeng', 'Pendidikan Anak'],
            'Agama' => ['Buku Agama', 'Filsafat'],
            'Teknologi' => ['Pemrograman', 'AI & Machine Learning'],
        ];

        foreach ($categories as $catName => $types) {
            $category = Category::create([
                'name' => $catName
            ]);

            foreach ($types as $typeName) {
                BookType::create([
                    'category_id' => $category->id,
                    'name' => $typeName
                ]);
            }
        }
    }
}
