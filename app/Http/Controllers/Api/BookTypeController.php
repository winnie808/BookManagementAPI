<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class BookTypeController extends Controller
{
    public function index()
    {
        return response()->json(BookType::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'BookTypeName' => ['required', 'string', 'max:255'],
            'BookTypePicture' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('BookTypePicture')) {
            $picturePath = $request->file('BookTypePicture')->storePublicly('categorie', 'public');

            if ($picturePath === false) {
                throw new RuntimeException('The book type picture could not be stored.');
            }

            $validated['BookTypePicture'] = $picturePath;
        }

        $bookType = BookType::create($validated);

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

        $validated = $request->validate([
            'BookTypeName' => ['sometimes', 'required', 'string', 'max:255'],
            'BookTypePicture' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('BookTypePicture')) {
            $picturePath = $request->file('BookTypePicture')->storePublicly('categorie', 'public');

            if ($picturePath === false) {
                throw new RuntimeException('The book type picture could not be stored.');
            }

            $oldPicturePath = $bookType->BookTypePicture;
            $validated['BookTypePicture'] = $picturePath;
            $bookType->update($validated);

            if ($oldPicturePath && ! Storage::disk('public')->delete($oldPicturePath)) {
                throw new RuntimeException('The previous book type picture could not be deleted.');
            }
        } else {
            $bookType->update($validated);
        }

        return response()->json($bookType, 200);
    }

    public function destroy($id)
    {
        $bookType = BookType::find($id);

        if (! $bookType) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($bookType->BookTypePicture && ! Storage::disk('public')->delete($bookType->BookTypePicture)) {
            throw new RuntimeException('The book type picture could not be deleted.');
        }

        $bookType->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
