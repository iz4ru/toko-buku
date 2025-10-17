<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index()
    {
        $x['logs'] = Auth::user()->logs()->orderByDesc('created_at')->get();

        return view('admin.contents.logs.log', $x);
    }
}
