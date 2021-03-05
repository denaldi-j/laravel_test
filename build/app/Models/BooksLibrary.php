<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksLibrary extends Model
{
    use HasFactory;

    protected $table = 'books_library';
    protected $primaryKey = 'id';
    protected $fillable = ['book_id', 'category_id'];

    public $timestamps = false;
}
