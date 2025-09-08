<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookDetail;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $x['books'] = Book::all();
        $x['bookDetails'] = BookDetail::all();

        return view('admin.book', $x);
    }
}
