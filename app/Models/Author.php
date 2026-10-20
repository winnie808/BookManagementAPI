<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $table = 'tblAuthor';
    protected $primaryKey = 'AuthorID';
    protected $guarded = [];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'tblBookAuthor', 'AuthorID', 'BookID');
    }
}
