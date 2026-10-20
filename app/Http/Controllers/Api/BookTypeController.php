<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookType;
use Illuminate\Http\Request;

class BookTypeController extends Controller
{
    public function index()
    {
        return response()->json(BookType::all(), 200);
    }

    public function store(Request $request)
    {
        $bookType = BookType::create($request->all());

        return response()->json($bookType, 201);
    }

    public function show($id)
    {
        $bookType = BookType::find($id);

        if (! $bookType) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($bookType, 200);
    }

    public function update(Request $request, $id)
    {
        $bookType = BookType::find($id);

        if (! $bookType) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $bookType->update($request->all());

        return response()->json($bookType, 200);
    }

    public function destroy($id)
    {
        $bookType = BookType::find($id);

        if (! $bookType) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $bookType->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
