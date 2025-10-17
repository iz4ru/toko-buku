<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionItem extends Model
{
    use HasFactory;

    protected $table = 'transaction_items';

    protected $fillable = [
        'transaction_id',
        'book_id',
        'transaction_code',
        'quantity',
        'price',
        'discount',
        'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
