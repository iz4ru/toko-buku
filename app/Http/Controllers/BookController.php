<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Category;
use App\Models\BookDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['category', 'bookType', 'bookDetail'])
            ->orderByDesc('created_at')
            ->get();

        $booksArray = $books
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'title' => $b->title,
                    'book_code' => $b->book_code,
                    'book_cover' => $b->book_cover,
                    'author' => $b->author,
                    'publisher' => $b->publisher,
                    'publication_year' => $b->publication_year,
                    'category' => [
                        'category' => $b->category->name ?? '-',
                        'book_type' => $b->bookType->name ?? '-',
                    ],
                    'book_detail' => [
                        'stock' => $b->bookDetail->stock ?? '-',
                        'price' => $b->bookDetail->price ?? '-',
                    ],
                ];
            })
            ->toArray();

        return view('admin.contents.book-management.book', [
            'books' => $books,
            'booksArray' => $booksArray,
        ]);
    }

    public function create()
    {
        $x['categories'] = Category::with('bookTypes')->get();
        return view('admin.contents.book-management.book-create', $x);
    }

    public function getBookTypes($categoryId)
    {
        $types = BookType::where('category_id', $categoryId)->pluck('name', 'id');
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'publisher' => 'required|string|max:255',
                'category' => 'required|exists:categories,id',
                'book_type' => 'required|exists:book_types,id',
                'publication_year' => 'required|date_format:Y-m',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'book_cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'category.exists' => 'Kategori tidak valid.',
                'book_type.exists' => 'Jenis buku tidak valid.',
                'price.min' => 'Harga tidak boleh kurang dari Rp0.',
                'book_cover.mimes' => 'Jenis file yang diupload harus berupa jpg, jpeg atau png.',
            ],
        );

        // Ambil kategori dan jenis buku
        $category = Category::findOrFail($request->category);
        $bookType = BookType::findOrFail($request->book_type);

        // Ambil inisial dua huruf pertama
        $categoryInitial = strtoupper(substr($category->name, 0, 2));
        $typeInitial = strtoupper(substr($bookType->name, 0, 2));

        // Ambil buku terakhir dari kategori + tipe yang sama
        $lastBook = Book::where('category_id', $category->id)->where('book_type_id', $bookType->id)->latest('id')->first();

        // Ambil angka terakhir dari kode sebelumnya (4 digit terakhir)
        if ($lastBook && preg_match('/\d{4}$/', $lastBook->book_code, $matches)) {
            $lastNumber = intval($matches[0]);
        } else {
            $lastNumber = 0;
        }

        // Tambah 1 dan padding biar tetap 4 digit
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        // Bentuk kode baru
        $bookCode = $categoryInitial . $typeInitial . $newNumber;

        // Simpan cover
        $coverName = Str::slug($request->title) . '.' . $request->file('book_cover')->getClientOriginalExtension();
        $path = $request->file('book_cover')->storeAs('book_covers', $coverName, 'public');

        // Simpan buku
        $book = Book::create([
            'book_code' => $bookCode,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'category_id' => $category->id,
            'book_type_id' => $bookType->id,
            'publication_year' => $request->publication_year,
            'description' => $request->description,
            'book_cover' => $path,
        ]);

        BookDetail::create([
            'book_id' => $book->id,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        Log::create([
            'action' => 'Tambah Buku',
            'module' => 'Manajemen Buku',
            'description' => 'Menambahkan buku baru',
        ]);

        return redirect()->route('admin.book')->with('success', 'Buku baru berhasil ditambahkan.');
    }

    public function generateCode($categoryId, $typeId)
    {
        $category = Category::findOrFail($categoryId);
        $type = BookType::findOrFail($typeId);

        $catInit = strtoupper(substr($category->name, 0, 2));
        $typeInit = strtoupper(substr($type->name, 0, 2));

        // ganti 'type_id' jadi 'book_type_id'
        $lastBook = Book::where('category_id', $categoryId)->where('book_type_id', $typeId)->latest('id')->first();

        $lastNumber = $lastBook ? intval(substr($lastBook->book_code, -4)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        $newCode = $catInit . $typeInit . $newNumber;

        return response()->json(['code' => $newCode]);
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $x['book'] = $book;
        $x['categories'] = Category::all();
        $x['bookTypes'] = BookType::where('category_id', $book->category_id)->get();

        return view('admin.contents.book-management.book-edit', $x);
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate(
            [
                'title' => 'required|string|max:255|unique:books,title,' . $book->id,
                'author' => 'required|string|max:255',
                'publisher' => 'required|string|max:255',
                'publication_year' => 'required|date_format:Y-m',
                'description' => 'nullable|string|max:1000',
                'book_cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'title.required' => 'Judul buku wajib diisi.',
                'title.unique' => 'Judul buku sudah ada.',
                'author.required' => 'Nama penulis wajib diisi.',
                'publisher.required' => 'Nama penerbit wajib diisi.',
                'publication_year.required' => 'Tahun terbit wajib diisi.',
                'publication_year.date_format' => 'Format tahun terbit harus Y-m (contoh: 2025-01).',
                'description.max' => 'Deskripsi maksimal 1000 karakter.',
                'book_cover.image' => 'File cover harus berupa gambar.',
                'book_cover.mimes' => 'Format gambar harus jpg, jpeg, atau png.',
                'book_cover.max' => 'Ukuran file cover maksimal 2MB.',
            ],
        );

        if ($request->hasFile('book_cover')) {
            if ($book->book_cover && Storage::exists('public/' . $book->book_cover)) {
                Storage::delete('public/' . $book->book_cover);
            }
            $coverPath = $request->file('book_cover')->store('book_covers', 'public');
            $validated['book_cover'] = $coverPath;
        }

        $book->update([
            'title' => trim($validated['title']),
            'author' => trim($validated['author']),
            'publisher' => trim($validated['publisher']),
            'publication_year' => $validated['publication_year'],
            'description' => $validated['description'] ?? null,
            'book_cover' => $validated['book_cover'] ?? $book->book_cover,
        ]);

        Log::create([
            'action' => 'Mengedit Buku',
            'module' => 'Manajemen Buku',
            'description' => 'Mengedit data buku',
        ]);

        return redirect()->route('admin.book')->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->categories()->count() > 0) {
            return redirect()
                ->route('admin.book')
                ->withErrors(['error' => 'Buku tidak bisa dihapus karena sudah memiliki kategori.']);
        }

        // Hapus cover kalau ada di storage
        if ($book->book_cover && Storage::exists('public/' . $book->book_cover)) {
            Storage::delete('public/' . $book->book_cover);
        }

        // Hapus book_detail dulu (karena foreign key)
        $book->bookDetail()->delete();

        // Hapus data buku
        $book->delete();

        Log::create([
            'action' => 'Hapus Buku',
            'module' => 'Manajemen Buku',
            'description' => 'Menghapus data buku',
        ]);

        return redirect()->route('admin.book')->with('success', 'Data buku berhasil dihapus!');
    }
}
