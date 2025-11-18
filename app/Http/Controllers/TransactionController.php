<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function indexAdmin()
    {
        $x['transactions'] = Transaction::with('transactionItems')->orderByDesc('transaction_date')->get();

        return view('admin.contents.transaction-management.transaction', $x);
    }

    public function indexCashier()
    {
        $x['transactions'] = Transaction::with('transactionItems')->orderByDesc('transaction_date')->get();

        return view('cashier.contents.transaction-management.transaction-history', $x);
    }

    public function indexOwner()
    {
        $x['transactions'] = Transaction::with('transactionItems')->orderByDesc('transaction_date')->get();

        return view('owner.contents.transaction-management.transaction-history', $x);
    }

    public function show($id)
    {
        $transaction = Transaction::with('transactionItems', 'user')->findOrFail($id);

        return view('admin.contents.transaction-management.transaction-detail', [
            'transaction' => $transaction,
        ]);
    }
    public function edit($id)
    {
        $transaction = Transaction::with('transactionItems', 'user')->findOrFail($id);

        if (Auth::user()->role === 'owner') {
            return view('owner.contents.transaction-management.transaction-edit', [
                'transaction' => $transaction,
            ]);
        }

        return view('cashier.contents.transaction-management.transaction-edit', [
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'transaction_type' => 'required|in:sale,return',
            'note' => 'nullable|string',
        ]);

        $transaction = Transaction::with('transactionItems.book.bookDetail')->findOrFail($id);

        if ($transaction->transaction_type !== $request->transaction_type) {
            foreach ($transaction->transactionItems as $item) {
                $bookDetail = $item->book->bookDetail ?? null;
                if ($bookDetail) {
                    if ($request->transaction_type === 'return') {
                        $bookDetail->stock += $item->quantity;
                    } elseif ($request->transaction_type === 'sale') {
                        $bookDetail->stock -= $item->quantity;
                    }
                    $bookDetail->save();
                }
            }
        }

        $transaction->transaction_type = $request->transaction_type;
        $transaction->note = $request->note;
        $transaction->save();

        if (Auth::user()->role === 'owner') {
            return redirect()->route('owner.transaction')->with('success', 'Transaksi berhasil diperbarui.');
        }

        return redirect()->route('cashier.transaction')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function receipt($id)
    {
        $transaction = Transaction::with(['transactionItems.book', 'user', 'discount'])->findOrFail($id);
        $transactions = $transaction->transactionItems;

        if (Auth::user()->role === 'owner') {
            return view('owner.contents.transaction-management.transaction-receipt', compact('transaction', 'transactions'));
        }

        return view('cashier.contents.transaction-management.transaction-receipt', compact('transaction', 'transactions'));
    }

    public function reportIndex(Request $request)
    {
        // Set default period (current month)
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Query transactions based on period
        $transactions = Transaction::with(['user', 'discount', 'transactionItems.book.bookDetail'])
            ->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ])
            ->orderBy('transaction_date', 'desc')
            ->paginate(15);

        // Calculate statistics
        $total_transactions = Transaction::whereBetween('transaction_date', [
            Carbon::parse($start_date)->startOfDay(),
            Carbon::parse($end_date)->endOfDay()
        ])->count();

        $total_revenue = Transaction::whereBetween('transaction_date', [
            Carbon::parse($start_date)->startOfDay(),
            Carbon::parse($end_date)->endOfDay()
        ])->sum('subtotal');

        // Calculate total discount from all transactions with discount
        $transactions_with_discount = Transaction::with('discount')
            ->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ])
            ->whereNotNull('discount_id')
            ->get();

        $total_discount = 0;
        foreach ($transactions_with_discount as $transaction) {
            if ($transaction->discount) {
                // Calculate total price before discount
                $price_before_discount = $transaction->subtotal / (1 - ($transaction->discount->percentage / 100));
                $discount_amount = $price_before_discount * ($transaction->discount->percentage / 100);
                $total_discount += $discount_amount;
            }
        }

        // Total books sold
        $total_books_sold = TransactionItem::whereHas('transaction', function($query) use ($start_date, $end_date) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        })->sum('quantity');

        // Best selling books (Top 5)
        $best_sellers = TransactionItem::with(['book.bookDetail'])
            ->whereHas('transaction', function($query) use ($start_date, $end_date) {
                $query->whereBetween('transaction_date', [
                    Carbon::parse($start_date)->startOfDay(),
                    Carbon::parse($end_date)->endOfDay()
                ]);
            })
            ->selectRaw('book_id, SUM(quantity) as total_sold')
            ->groupBy('book_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('owner.contents.sale-report.sale-report', compact(
            'transactions',
            'start_date',
            'end_date',
            'total_transactions',
            'total_revenue',
            'total_discount',
            'total_books_sold',
            'best_sellers'
        ));
    }

    public function reportPrint(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Query transactions based on period
        $transactions = Transaction::with(['user', 'discount', 'transactionItems.book.bookDetail'])
            ->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ])
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Calculate statistics
        $total_transactions = $transactions->count();
        $total_revenue = $transactions->sum('subtotal');

        // Calculate total discount
        $total_discount = 0;
        foreach ($transactions->where('discount_id', '!=', null) as $transaction) {
            if ($transaction->discount) {
                $price_before_discount = $transaction->subtotal / (1 - ($transaction->discount->percentage / 100));
                $discount_amount = $price_before_discount * ($transaction->discount->percentage / 100);
                $total_discount += $discount_amount;
            }
        }

        // Total books sold
        $total_books_sold = TransactionItem::whereHas('transaction', function($query) use ($start_date, $end_date) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        })->sum('quantity');

        // Best selling books (Top 5)
        $best_sellers = TransactionItem::with(['book.bookDetail'])
            ->whereHas('transaction', function($query) use ($start_date, $end_date) {
                $query->whereBetween('transaction_date', [
                    Carbon::parse($start_date)->startOfDay(),
                    Carbon::parse($end_date)->endOfDay()
                ]);
            })
            ->selectRaw('book_id, SUM(quantity) as total_sold')
            ->groupBy('book_id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('owner.contents.sale-report.print', compact(
            'transactions',
            'start_date',
            'end_date',
            'total_transactions',
            'total_revenue',
            'total_discount',
            'total_books_sold',
            'best_sellers'
        ));
    }

    public function exportExcel(Request $request)
    {
        $start_date = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? Carbon::now()->endOfMonth()->format('Y-m-d');

        // Query transactions
        $transactions = Transaction::with(['user', 'discount', 'transactionItems.book.bookDetail'])
            ->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ])
            ->orderBy('transaction_date', 'desc')
            ->get();

        // Calculate statistics
        $total_transactions = $transactions->count();
        $total_revenue = $transactions->sum('subtotal');
        
        $total_discount = 0;
        foreach ($transactions->where('discount_id', '!=', null) as $transaction) {
            if ($transaction->discount) {
                $price_before_discount = $transaction->subtotal / (1 - ($transaction->discount->percentage / 100));
                $discount_amount = $price_before_discount * ($transaction->discount->percentage / 100);
                $total_discount += $discount_amount;
            }
        }

        $total_books_sold = TransactionItem::whereHas('transaction', function($query) use ($start_date, $end_date) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        })->sum('quantity');

        // Create CSV
        $filename = 'Sales_Report_' . Carbon::parse($start_date)->format('d-m-Y') . '_' . Carbon::parse($end_date)->format('d-m-Y') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions, $total_transactions, $total_revenue, $total_discount, $total_books_sold, $start_date, $end_date) {
            $file = fopen('php://output', 'w');
            
            // BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Report Header
            fputcsv($file, ['PAPERY SALES REPORT']);
            fputcsv($file, ['Period', Carbon::parse($start_date)->format('d/m/Y') . ' - ' . Carbon::parse($end_date)->format('d/m/Y')]);
            fputcsv($file, ['Printed', Carbon::now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);
            
            // Summary
            fputcsv($file, ['SUMMARY']);
            fputcsv($file, ['Total Transactions', $total_transactions]);
            fputcsv($file, ['Total Revenue', 'Rp ' . number_format($total_revenue, 0, ',', '.')]);
            fputcsv($file, ['Total Discount', 'Rp ' . number_format($total_discount, 0, ',', '.')]);
            fputcsv($file, ['Total Books Sold', $total_books_sold]);
            fputcsv($file, []);
            
            // Table Header
            fputcsv($file, ['No', 'Date', 'Transaction Code', 'Cashier', 'Customer', 'Total Price', 'Discount', 'Subtotal', 'Paid', 'Change', 'Payment Method', 'Type', 'Note']);
            
            // Transaction Data
            $no = 1;
            foreach ($transactions as $transaction) {
                $transaction_code = $transaction->transactionItems->pluck('transaction_code')->unique()->join(', ');
                $discount = 0;
                $total_price = $transaction->subtotal;
                
                if ($transaction->discount) {
                    $price_before_discount = $transaction->subtotal / (1 - ($transaction->discount->percentage / 100));
                    $discount = $price_before_discount * ($transaction->discount->percentage / 100);
                    $total_price = $price_before_discount;
                }
                
                fputcsv($file, [
                    $no++,
                    $transaction->transaction_date ? Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') : '-',
                    $transaction_code,
                    $transaction->user->name ?? '-',
                    $transaction->customer_name,
                    'Rp ' . number_format($total_price, 0, ',', '.'),
                    'Rp ' . number_format($discount, 0, ',', '.'),
                    'Rp ' . number_format($transaction->subtotal, 0, ',', '.'),
                    'Rp ' . number_format($transaction->paid, 0, ',', '.'),
                    'Rp ' . number_format($transaction->spare_change, 0, ',', '.'),
                    strtoupper($transaction->payment_method),
                    strtoupper($transaction->transaction_type),
                    $transaction->note ?? '-'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
