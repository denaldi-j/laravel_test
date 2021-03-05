<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title', 'author', 'description', 'cover'
    ];

    public function category()
    {
        return $this->hasManyThrough('App\Models\Category', 'App\Models\BooksLibrary',  'book_id', 'id', 'id', 'category_id');
    }

}
