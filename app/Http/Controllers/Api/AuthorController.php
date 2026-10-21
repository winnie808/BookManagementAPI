<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AuthorController extends Controller
{
    public function index()
    {
        return response()->json(Author::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->except('Photo');

        if ($request->hasFile('Photo')) {
            $request->validate([
                'Photo' => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            ]);

            $photoPath = $request->file('Photo')->storePublicly('categorie', 'public');

            if ($photoPath === false) {
                throw new RuntimeException('The author photo could not be stored.');
            }

            $data['Photo'] = $photoPath;
        }

        $author = Author::create($data);

        return response()->json($author, 201);
    }

    public function show($id)
    {
        $author = Author::find($id);

        if (! $author) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($author, 200);
    }

    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        if (! $author) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->except('Photo');

        if ($request->hasFile('Photo')) {
            $request->validate([
                'Photo' => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            ]);

            $photoPath = $request->file('Photo')->storePublicly('categorie', 'public');

            if ($photoPath === false) {
                throw new RuntimeException('The author photo could not be stored.');
            }

            $oldPhotoPath = $author->Photo;
            $data['Photo'] = $photoPath;
            $author->update($data);

            if ($oldPhotoPath && ! Storage::disk('public')->delete($oldPhotoPath)) {
                throw new RuntimeException('The previous author photo could not be deleted.');
            }
        } else {
            $author->update($data);
        }

        return response()->json($author, 200);
    }

    public function destroy($id)
    {
        $author = Author::find($id);

        if (! $author) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($author->Photo && ! Storage::disk('public')->delete($author->Photo)) {
            throw new RuntimeException('The author photo could not be deleted.');
        }

        $author->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
