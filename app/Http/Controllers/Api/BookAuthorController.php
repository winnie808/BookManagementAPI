<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookAuthor;
use Illuminate\Http\Request;

class BookAuthorController extends Controller
{
    public function index()
    {
        return response()->json(BookAuthor::all(), 200);
    }

    public function store(Request $request)
    {
        $bookAuthor = BookAuthor::create($request->all());

        return response()->json($bookAuthor, 201);
    }

    public function show($bookId, $authorId = null)
    {
        if ($authorId === null) {
            $bookAuthor = BookAuthor::where('BookID', $bookId)->get();
            return response()->json($bookAuthor, 200);
        }

        $bookAuthor = BookAuthor::where('BookID', $bookId)->where('AuthorID', $authorId)->first();

        if (! $bookAuthor) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($bookAuthor, 200);
    }

    public function update(Request $request, $bookId, $authorId = null)
    {
        if ($authorId === null) {
            return response()->json(['message' => 'Book and Author combination is required'], 400);
        }

        $bookAuthor = BookAuthor::where('BookID', $bookId)->where('AuthorID', $authorId)->first();

        if (! $bookAuthor) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $bookAuthor->update($request->all());

        return response()->json($bookAuthor, 200);
    }

    public function destroy($bookId, $authorId = null)
    {
        if ($authorId === null) {
            return response()->json(['message' => 'Book and Author combination is required'], 400);
        }

        $bookAuthor = BookAuthor::where('BookID', $bookId)->where('AuthorID', $authorId)->first();

        if (! $bookAuthor) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $bookAuthor->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
