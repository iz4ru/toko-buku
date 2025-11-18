<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $x['logs'] = Auth::user()->logs()->orderByDesc('created_at')->get();
            return view('admin.contents.logs.log', $x);
        } elseif (Auth::user()->role === 'cashier') {
            $x['logs'] = Auth::user()->logs()->orderByDesc('created_at')->get();
            return view('cashier.contents.logs.log', $x);
        } elseif (Auth::user()->role === 'owner') {
            $x['logs'] = Log::orderByDesc('created_at')->get();
            return view('owner.contents.logs.log', $x);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
