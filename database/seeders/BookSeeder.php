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
            'book_cover' => 'great_gatsby.jpg',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora quaerat, quas repellendus reprehenderit adipisci fugit obcaecati voluptate ut iste cupiditate quisquam, dicta nisi quo vero voluptatem dolorem id corporis recusandae fugiat veniam excepturi voluptatibus rem. Aliquid alias sint, debitis voluptatibus porro eaque dolorem omnis consectetur sequi ipsum eveniet aspernatur minima harum distinctio reprehenderit autem libero iste ullam tempora deserunt est perspiciatis. Quis aperiam laborum libero laboriosam expedita, vel ad voluptatem ipsa cum soluta consequatur maiores officiis odit saepe nisi hic, suscipit fuga perspiciatis. Impedit officia molestiae neque! Exercitationem, asperiores dolores quos excepturi animi amet id illo reiciendis, officiis a itaque?',
        ]);
    }
}
