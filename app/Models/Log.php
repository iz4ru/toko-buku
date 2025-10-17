<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent'
    ];

    // Optional: otomatis isi user_id kalau user sedang login
    protected static function booted()
    {
        static::creating(function ($log) {
            if (Auth::check() && empty($log->user_id)) {
                $log->user_id = Auth::id();
            }
            $log->ip_address = request()->ip();
            $log->user_agent = request()->header('User-Agent');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
