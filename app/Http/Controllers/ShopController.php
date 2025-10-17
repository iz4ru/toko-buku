<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Book;
use App\Models\Category;
use App\Models\Discount;
use App\Models\BookDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category', 'bookType', 'bookDetail']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%$q%")
                    ->orWhere('book_code', 'like', "%$q%")
                    ->orWhere('publisher', 'like', "%$q%")
                    ->orWhere('author', 'like', "%$q%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->latest()->paginate(9);
        $categories = Category::with('bookTypes')
            ->get()
            ->map(function ($cat) {
                return [
                    'category_name' => $cat->name,
                    'types' => $cat->bookTypes->map(function ($type) {
                        return [
                            'id' => $type->id,
                            'name' => $type->name,
                        ];
                    }),
                ];
            });
        return view('cashier.contents.transaction.transaction', compact('books', 'categories'));
    }

    public function indexCheckout()
    {
        $books = Book::with(['category', 'bookType', 'bookDetail'])->get();
        $discounts = Discount::where('status', 1)->get();
        $cart = session()->get('cart', []);

        foreach ($cart as $book_id => $item) {
            $bookDetail = BookDetail::where('book_id', $book_id)->first();
            if ($bookDetail) {
                $cart[$book_id]['stock'] = $bookDetail->stock;

                if ($item['quantity'] > $bookDetail->stock) {
                    $cart[$book_id]['quantity'] = $bookDetail->stock;
                }
            }
        }

        session()->put('cart', $cart);

        return view('cashier.contents.transaction.transaction', compact('books', 'cart', 'discounts'));
    }

    public function showCheckoutForm()
    {
        $cart = session()->get('cart', []);
        $discounts = Discount::where('status', 1)->get();

        if (empty($cart)) {
            return redirect()->route('cashier.shop')->with('error', 'Keranjang masih kosong');
        }

        return view('cashier.contents.transaction.transaction-checkout', compact('cart', 'discounts'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,cashless',
            'paid' => 'nullable|numeric',
            'discount_id' => 'nullable|integer',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $discountAmount = 0;

        if ($request->discount_id) {
            $discount = Discount::find($request->discount_id);
            if ($discount && $discount->status == 1) {
                $discountAmount = $subtotal * ($discount->percentage / 100);
            }
        }

        $total = $subtotal - $discountAmount;
        $spareChange = 0;

        if ($request->payment_method == 'cash') {
            if ($request->paid < $total) {
                return back()->with('error', 'Jumlah bayar tidak mencukupi');
            }
            $spareChange = $request->paid - $total;
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'transaction_code' => 'TRX-' . strtoupper(uniqid()),
                'customer_name' => $request->customer_name,
                'payment_method' => $request->payment_method,
                'discount_id' => $request->discount_id,
                'total' => $total,
                'paid' => $request->paid ?? $total,
                'spare_change' => $spareChange,
                'note' => $request->note,
                'user_id' => Auth::id(),
            ]);

            foreach ($cart as $id => $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                ]);

                // Kurangi stok
                $bookDetail = BookDetail::where('book_id', $id)->first();
                if ($bookDetail) {
                    $bookDetail->decrement('stock', $item['quantity']);
                }
            }

            Log::create([
                'user_id' => Auth::id(),
                'activity' => 'Melakukan transaksi penjualan: ' . $transaction->transaction_code,
            ]);

            DB::commit();
            session()->forget('cart');

            return redirect()->route('cashier.shop')->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi');
        }
    }

    // Tambahkan buku ke keranjang
    public function addToCart(Request $request)
    {
        $book = Book::with('bookDetail')->findOrFail($request->book_id);
        $cart = session()->get('cart', []);

        // Cegah tambah kalau stok 0
        if ($book->bookDetail->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok buku ini kosong',
            ]);
        }

        if (isset($cart[$book->id])) {
            if ($cart[$book->id]['quantity'] < $book->bookDetail->stock) {
                $cart[$book->id]['quantity']++;
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi',
                ]);
            }
        } else {
            $cart[$book->id] = [
                'title' => $book->title,
                'price' => $book->bookDetail->price,
                'stock' => $book->bookDetail->stock,
                'quantity' => 1,
                'cover' => $book->book_cover,
            ];
        }

        session()->put('cart', $cart);
        return response()->json([
            'status' => 'success',
            'message' => 'Buku berhasil ditambahkan ke keranjang',
        ]);
    }

    // Ubah jumlah di keranjang
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $book = Book::with('bookDetail')->findOrFail($request->book_id);

        if (isset($cart[$book->id])) {
            $quantity = max(1, (int) $request->quantity);

            if ($quantity > $book->bookDetail->stock) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok tidak mencukupi',
                ]);
            }

            $cart[$book->id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Keranjang berhasil diperbarui',
        ]);
    }

    // Hapus dari keranjang
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang');
    }

    // Checkout transaksi
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->withErrors(['error' => 'Keranjang masih kosong.']);
        }

        // Validasi input
        $request->validate(
            [
                'customer_name' => 'required|string|max:255',
                'payment_method' => 'required|in:cash,cashless',
                'paid' => 'required|numeric|min:0',
                'spare_change' => 'required|numeric|min:0',
                'note' => 'nullable|string',
                'discount_id' => 'nullable|exists:discounts,id',
            ],
            [
                'customer_name.required' => 'Nama pelanggan wajib diisi.',
                'customer_name.string' => 'Nama pelanggan harus berupa teks.',
                'customer_name.max' => 'Nama pelanggan terlalu panjang.',

                'payment_method.required' => 'Metode pembayaran wajib dipilih.',
                'payment_method.in' => 'Metode pembayaran tidak valid.',

                'paid.required' => 'Jumlah pembayaran wajib diisi.',
                'paid.numeric' => 'Jumlah pembayaran harus berupa angka.',
                'paid.min' => 'Jumlah pembayaran tidak boleh kurang dari 0.',

                'spare_change.required' => 'Kembalian wajib diisi.',
                'spare_change.numeric' => 'Kembalian harus berupa angka.',
                'spare_change.min' => 'Kembalian tidak boleh kurang dari 0.',

                'note.string' => 'Catatan harus berupa teks.',

                'discount_id.exists' => 'Diskon tidak ditemukan.',
            ],
        );

        DB::beginTransaction();
        try {
            // Hitung subtotal
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            // Hitung diskon jika ada
            $discountAmount = 0;
            if ($request->discount_id) {
                $discount = Discount::find($request->discount_id);
                if ($discount && $discount->status == 1) {
                    $discountAmount = $subtotal * ($discount->percentage / 100);
                }
            }

            $total = $subtotal - $discountAmount;

            // Generate transaction code
            $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'discount_id' => $request->discount_id,
                'customer_name' => $request->customer_name,
                'subtotal' => $total,
                'paid' => $request->paid,
                'spare_change' => $request->spare_change,
                'transaction_date' => now(),
                'transaction_type' => 'sale',
                'payment_method' => $request->payment_method,
                'note' => $request->note ?? 'Transaksi penjualan toko buku',
            ]);

            // Buat transaction items dan update stok
            foreach ($cart as $bookId => $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $bookId,
                    'transaction_code' => $transactionCode,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update stok
                $bookDetail = BookDetail::where('book_id', $bookId)->first();
                if ($bookDetail) {
                    $bookDetail->decrement('stock', $item['quantity']);
                }
            }

            // Log aktivitas
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'Transaksi Penjualan',
                'module' => 'Kasir',
                'description' => "Melakukan transaksi penjualan dengan kode {$transactionCode} untuk pelanggan {$request->customer_name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            session()->forget('cart');

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil diproses',
                'transaction_code' => $transactionCode,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
