<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'code',
        'percentage',
        'status'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
