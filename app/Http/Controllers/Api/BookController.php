<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return response()->json(Book::with('bookType')->with('authors')->get(), 200);
    }

    public function store(Request $request)
    {
        $book = Book::create($request->all());

        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = Book::with('bookType')->with('authors')->find($id);

        if (! $book) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($book, 200);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (! $book) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $book->update($request->all());

        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        if (! $book) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
