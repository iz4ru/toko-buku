<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'discount_id',
        'subtotal',
        'paid',
        'spare_change',
        'transaction_date',
        'transaction_type',
        'payment_method',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
