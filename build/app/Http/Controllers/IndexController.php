<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $data = Category::with(['book'])->get();
        return view('index', ['data' => $data]);
    }

    public function show_book($id)
    {
        $book = Book::where('id', $id)->first();
        return view('book_detail', ['data' => $book]);
    }

    public function show_category($id)
    {
        $category = Category::where('id', $id)->with(['book'])->first();
        return view('category_detail', ['data' => $category]);
    }
}
