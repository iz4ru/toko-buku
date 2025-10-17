<?php

namespace App\Http\Controllers;

use App\Models\BookDetail;
use Illuminate\Http\Request;

class BookDetailController extends Controller
{
    public function index()
    {
        $x['bookDetails'] = BookDetail::orderByDesc('created_at')->get();

        return view('admin.contents.book-detail-management.book-detail', $x);
    }

    public function editStock($id)
    {
        $x['bookDetail'] = BookDetail::findOrFail($id);

        return view('admin.contents.book-detail-management.book-detail-edit-stock', $x);
    }

    public function editPrice($id)
    {
        $x['bookDetail'] = BookDetail::findOrFail($id);

        return view('admin.contents.book-detail-management.book-detail-edit-price', $x);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate(
            [
                'action' => 'required|in:add,reduce',
                'amount' => 'required|integer|min:1|max:2147483647',
            ],
            [
                'action.required' => 'Pilih aksi terlebih dahulu.',
                'action.in' => 'Aksi tidak valid, pilih antara tambah atau kurangi stok.',
                'amount.required' => 'Jumlah stok wajib diisi.',
                'amount.integer' => 'Jumlah stok harus berupa angka.',
                'amount.min' => 'Jumlah stok minimal 1.',
                'amount.max' => 'Jumlah stok terlalu besar.',
            ],
        );

        $bookDetail = BookDetail::findOrFail($id);
        $amount = $request->amount;

        if ($request->action === 'add') {
            if ($bookDetail->stock + $amount > 2147483647) {
                return back()
                    ->withErrors([
                        'amount' => 'Total stok melebihi batas maksimum.',
                    ])
                    ->withInput();
            }
            $bookDetail->stock += $amount;
        } else {
            if ($bookDetail->stock < $amount) {
                return back()
                    ->withErrors([
                        'amount' => 'Stok tidak cukup untuk dikurangi. Stok saat ini: ' . $bookDetail->stock . '.',
                    ])
                    ->withInput();
            }
            $bookDetail->stock -= $amount;
        }

        $bookDetail->save();

        return redirect()->route('admin.book_detail')->with('success', 'Data stok berhasil diperbarui.');
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate(
            [
                'price' => 'required|integer|min:1|max:2147483647',
            ],
            [
                'price.required' => 'Harga wajib diisi.',
                'price.integer' => 'Harga harus berupa angka.',
                'price.min' => 'Harga minimal 1 rupiah.',
                'price.max' => 'Harga terlalu besar.',
            ],
        );

        $bookDetail = BookDetail::findOrFail($id);

        if ($request->price > 2147483647) {
            return back()
                ->withErrors([
                    'price' => 'Harga melebihi batas maksimum yang diizinkan.',
                ])
                ->withInput();
        }

        $bookDetail->price = $request->price;
        $bookDetail->save();

        return redirect()->route('admin.book_detail')->with('success', 'Harga buku berhasil diperbarui.');
    }
}
