<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $x['transactions'] = Transaction::with('transactionItems')->orderByDesc('transaction_date')->get();

        return view('admin.contents.transaction-management.transaction', $x);
    }

    public function show($id)
    {
        $transaction = Transaction::with('transactionItems', 'user')->findOrFail($id);

        return view('admin.contents.transaction-management.transaction-detail', [
            'transaction' => $transaction,
        ]);
    }
}
