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
        'reduce'
    ];

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }
}
