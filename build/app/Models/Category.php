<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function book()
    {
        return $this->hasManyThrough('App\Models\Book', 'App\Models\BooksLibrary', 'category_id', 'id', 'id', 'book_id');
    }

}
