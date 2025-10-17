<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\BookDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data umum
        $x['bookTotal'] = Book::count();
        $x['categoryTotal'] = Category::count();
        $x['stockTotal'] = BookDetail::sum('stock');
        $x['nearEmpty'] = BookDetail::where('stock', '<=', 10)->count();

        // Grafik stok buku per kategori
        $x['stockPerCategory'] = Category::with(['book.bookDetail'])
            ->get(['id', 'name'])
            ->map(function ($category) {
                $totalStock = $category->book->sum(function ($book) {
                    return $book->bookDetail->sum('stock');
                });
                return [
                    'category' => $category->name,
                    'total_stock' => $totalStock,
                ];
            });

        // Grafik transaksi per bulan
        $x['transactionsPerMonth'] = Transaction::select(DB::raw('MONTH(transaction_date) as month'), DB::raw('COUNT(*) as total'))->groupBy('month')->orderBy('month')->get()->mapWithKeys(fn($t) => [$t->month => $t->total]);

        // Tambahan khusus kasir
        if ($user->role == 'cashier') {
            // Transaksi hari ini
            $today = now()->toDateString();

            $x['transactionsToday'] = Transaction::whereDate('transaction_date', $today)->count();

            // Buku terjual hari ini (asumsinya punya relasi transaction_items)
            $x['booksSoldToday'] = TransactionItem::whereHas('transaction', function ($q) use ($today) {
                $q->whereDate('transaction_date', $today);
            })->sum('quantity');

            // Pendapatan hari ini
            $x['incomeToday'] = Transaction::whereDate('transaction_date', $today)->sum('subtotal');

            // Buku dengan stok menipis dan habis
            $x['lowStockBooks'] = BookDetail::where('stock', '<=', 10)->with('book')->orderBy('stock', 'asc')->get();

            // Grafik penjualan 7 hari terakhir
            $x['salesLast7Days'] = TransactionItem::select(DB::raw('DATE(transactions.transaction_date) as date'), DB::raw('SUM(transaction_items.quantity) as total_sold'))
                ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
                ->whereBetween('transactions.transaction_date', [now()->subDays(6), now()])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Buku terlaris minggu ini
            $x['topBooksThisWeek'] = TransactionItem::select('book_id', DB::raw('SUM(quantity) as total_sold'))
                ->whereHas('transaction', function ($q) {
                    $q->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()]);
                })
                ->groupBy('book_id')
                ->with('book')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get();
        }

        // Arahkan ke dashboard sesuai role
        if ($user->role == 'admin') {
            return view('admin.contents.dashboard', $x);
        } elseif ($user->role == 'cashier') {
            return view('cashier.contents.dashboard', $x);
        } elseif ($user->role == 'owner') {
            return view('owner.dashboard', $x);
        } else {
            abort(403, 'Unauthorized Action');
        }
    }
}
