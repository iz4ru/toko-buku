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

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%$q%")
                    ->orWhere('book_code', 'like', "%$q%")
                    ->orWhere('publisher', 'like', "%$q%")
                    ->orWhere('author', 'like', "%$q%");
            });
        }

        // Filter by book_type
        if ($request->filled('category_id')) {
            $query->where('book_type_id', $request->category_id);
        }

        $books = $query->latest()->paginate(12);

        // map untuk array
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

        return view('cashier.contents.transaction.transaction', compact('books', 'categories', 'booksArray'));
    }

    // Form checkout
    public function showCheckoutForm()
    {
        $cart = session()->get('cart', []);
        $discounts = Discount::where('status', 1)->get();

        if (empty($cart)) {
            return redirect()->route('cashier.shop')->with('error', 'Keranjang masih kosong');
        }

        return view('cashier.contents.transaction.transaction-checkout', compact('cart', 'discounts'));
    }

    // Tambah buku ke keranjang
    public function addToCart(Request $request)
    {
        $book = Book::with('bookDetail')->findOrFail($request->book_id);
        $cart = session()->get('cart', []);

        // Cek stok
        if (!$book->bookDetail || $book->bookDetail->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok buku ini kosong',
            ]);
        }

        // Jika sudah ada di cart, tambah quantity
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
            // Tambah item baru ke cart
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

    // Update quantity di keranjang
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $book = Book::with('bookDetail')->findOrFail($request->book_id);

        if (!isset($cart[$book->id])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item tidak ditemukan di keranjang',
            ]);
        }

        $quantity = max(1, (int) $request->quantity);

        // Cek stok
        if ($quantity > $book->bookDetail->stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi (tersedia: ' . $book->bookDetail->stock . ')',
            ]);
        }

        $cart[$book->id]['quantity'] = $quantity;
        session()->put('cart', $cart);

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

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang');
    }

    // Proses checkout
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cashier.shop')->with('error', 'Keranjang masih kosong.');
        }

        // Validasi input
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,cashless',
            'paid' => 'required|numeric|min:0',
            'discount_id' => 'nullable|exists:discounts,id',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Hitung subtotal
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            // Hitung diskon jika ada
            $discountAmount = 0;
            if (!empty($validated['discount_id'])) {
                $discount = Discount::find($validated['discount_id']);
                if ($discount && $discount->status == 1) {
                    $discountAmount = $subtotal * ($discount->percentage / 100);
                }
            }

            $total = $subtotal - $discountAmount;

            // Validasi pembayaran cash
            if ($validated['payment_method'] === 'cash' && $validated['paid'] < $total) {
                return redirect()->route('cashier.shop')->with('error', 'Jumlah bayar tidak mencukupi.');
            }

            $spareChange = $validated['payment_method'] === 'cash' ? $validated['paid'] - $total : 0;

            // Generate transaction code
            $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad(Transaction::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'discount_id' => $validated['discount_id'] ?? null,
                'customer_name' => $validated['customer_name'],
                'subtotal' => $subtotal,
                'total' => $total,
                'paid' => $validated['paid'],
                'spare_change' => $spareChange,
                'transaction_date' => now(),
                'payment_method' => $validated['payment_method'],
                'note' => $validated['note'] ?? 'Transaksi penjualan toko buku',
            ]);

            // Buat transaction items & update stok
            foreach ($cart as $bookId => $item) {
                $bookDetail = BookDetail::where('book_id', $bookId)->first();

                if (!$bookDetail || $bookDetail->stock < $item['quantity']) {
                    DB::rollBack();
                    return redirect()
                        ->route('cashier.shop')
                        ->with('error', 'Stok buku "' . $item['title'] . '" tidak mencukupi.');
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $bookId,
                    'transaction_code' => $transactionCode,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $bookDetail->decrement('stock', $item['quantity']);
            }

            // Log aktivitas
            Log::create([
                'user_id' => Auth::id(),
                'action' => 'Melakukan Penjualan',
                'module' => 'Transaksi',
                'description' => "Melakukan transaksi penjualan dengan kode {$transactionCode} untuk pelanggan {$validated['customer_name']}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            // Hapus cart
            session()->forget('cart');

            return redirect()
                ->route('cashier.shop')
                ->with('success', "Transaksi berhasil diproses. Kode: {$transactionCode}, Total: {$total}, Kembalian: {$spareChange}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
