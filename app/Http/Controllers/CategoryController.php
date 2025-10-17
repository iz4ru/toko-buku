<?php

namespace App\Http\Controllers;

use App\Models\BookType;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $x['categories'] = Category::orderByDesc('created_at')->get();

        return view('admin.contents.category-management.category', $x);
    }

    public function create()
    {
        $x['categories'] = Category::all();

        return view('admin.contents.category-management.category-create', $x);
    }

    public function store(Request $request)
    {
        if (empty($request->book_types) || !collect($request->book_types)->filter(fn($t) => trim($t) != '')->count()) {
            return back()
                ->withErrors([
                    'book_types' => 'Isi jenis buku minimal satu.',
                ])
                ->withInput();
        }

        $request->validate(
            [
                'category_name' => 'required|string|max:255|unique:categories,name',
                'book_types' => 'nullable|array',
                'book_types.*' => 'nullable|string|max:255',
            ],
            [
                'category_name.required' => 'Nama kategori wajib diisi.',
                'category_name.string' => 'Nama kategori harus berupa teks.',
                'category_name.max' => 'Nama kategori maksimal 255 karakter.',
                'category_name.unique' => 'Nama kategori sudah terdaftar.',
                'book_types.array' => 'Tipe buku harus dalam bentuk array.',
                'book_types.*.string' => 'Setiap tipe buku harus berupa teks.',
                'book_types.*.max' => 'Setiap tipe buku maksimal 255 karakter.',
            ],
        );
        $category = Category::create([
            'name' => $request->category_name,
        ]);

        if ($request->book_types) {
            foreach ($request->book_types as $type) {
                if (trim($type) != '') {
                    BookType::create([
                        'category_id' => $category->id,
                        'name' => $type,
                    ]);
                }
            }
        }

        return redirect()->route('admin.category')->with('success', 'Kategori dan jenis buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $x['category'] = Category::with('bookTypes')->findOrFail($id);
        return view('admin.contents.category-management.category-edit', $x);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate(
            [
                'category_name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'book_types' => 'nullable|array',
                'book_types.*' => 'nullable|string|max:255',
            ],
            [
                'category_name.required' => 'Nama kategori wajib diisi.',
                'category_name.string' => 'Nama kategori harus berupa teks.',
                'category_name.max' => 'Nama kategori maksimal 255 karakter.',
                'category_name.unique' => 'Nama kategori sudah terdaftar.',
                'book_types.array' => 'Tipe buku harus dalam bentuk array.',
                'book_types.*.string' => 'Setiap tipe buku harus berupa teks.',
                'book_types.*.max' => 'Setiap tipe buku maksimal 255 karakter.',
            ],
        );

        // update nama kategori
        $category->update(['name' => $request->category_name]);

        // hapus semua tipe buku lama
        $category->bookTypes()->delete();

        // tambahin ulang tipe buku yang baru diinput
        if ($request->book_types) {
            foreach ($request->book_types as $type) {
                if (trim($type) != '') {
                    BookType::create([
                        'category_id' => $category->id,
                        'name' => $type,
                    ]);
                }
            }
        }

        return redirect()->route('admin.category')->with('success', 'Kategori dan jenis buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->book()->count() > 0) {
            return redirect()
                ->route('admin.category')
                ->withErrors(['error' => 'Kategori ini sudah dipakai untuk buku tertentu, tidak bisa dihapus.']);
        }

        $category->delete();

        return redirect()->route('admin.category')->with('success', 'Kategori berhasil dihapus.');
    }
}
