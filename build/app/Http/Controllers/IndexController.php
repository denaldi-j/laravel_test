<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $data = Category::with(['book'])->get();
        return view('index', ['data' => $data]);
    }
}
