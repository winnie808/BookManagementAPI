<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'tblBook';
    protected $primaryKey = 'BookID';
    protected $guarded = [];

    public function bookType()
    {
        return $this->belongsTo(BookType::class, 'BookTypeID', 'BookTypeID');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'tblBookAuthor', 'BookID', 'AuthorID');
    }
}
