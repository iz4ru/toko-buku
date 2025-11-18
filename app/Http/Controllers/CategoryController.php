<?php

namespace App\Http\Controllers;

use App\Models\BookType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderByDesc('created_at')->get();

        if (Auth::user()->role === 'owner') {
            return view('owner.contents.category-management.category', [
                'categories' => $categories,
            ]);
        }

        return view('admin.contents.category-management.category', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        if (Auth::user()->role === 'owner') {
            return view('owner.contents.category-management.category-create', [
                'categories' => $categories,
            ]);
        }

        return view('admin.contents.category-management.category-create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        if (empty($request->book_types) || !collect($request->book_types)->filter(fn($t) => trim($t) != '')->count()) {
            return back()
                ->withErrors(['book_types' => 'Isi jenis buku minimal satu.'])
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

        $category = Category::create(['name' => $request->category_name]);

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

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.category')->with('success', 'Kategori dan jenis buku berhasil ditambahkan.');
        }

        return redirect()->route('admin.category')->with('success', 'Kategori dan jenis buku berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = Category::with('bookTypes')->findOrFail($id);

        if (Auth::user()->role === 'owner') {
            return view('owner.contents.category-management.category-edit', [
                'category' => $category,
            ]);
        }

        return view('admin.contents.category-management.category-edit', [
            'category' => $category,
        ]);
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

        $category->update(['name' => $request->category_name]);
        $category->bookTypes()->delete();

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

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.category')->with('success', 'Kategori dan jenis buku berhasil diperbarui.');
        }

        return redirect()->route('admin.category')->with('success', 'Kategori dan jenis buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->book()->count() > 0) {
            if (Auth::user()->role === 'owner') {
                return redirect()
                    ->route('owner.category')
                    ->withErrors(['error' => 'Kategori ini sudah dipakai untuk buku tertentu, tidak bisa dihapus.']);
            }

            return redirect()
                ->route('admin.category')
                ->withErrors(['error' => 'Kategori ini sudah dipakai untuk buku tertentu, tidak bisa dihapus.']);
        }

        $category->delete();

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.category')->with('success', 'Kategori berhasil dihapus.');
        }

        return redirect()->route('admin.category')->with('success', 'Kategori berhasil dihapus.');
    }
}
