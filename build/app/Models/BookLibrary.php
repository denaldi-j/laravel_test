<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLibrary extends Model
{
    use HasFactory;

    protected $table = 'book_libraries';
    protected $primaryKey = 'id';
    protected $fillable = ['book_id', 'category_id'];

    public $timestamps = false;
}
