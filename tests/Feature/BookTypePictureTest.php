<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookTypePictureTest extends TestCase
{
    use RefreshDatabase;

    public function test_book_type_picture_can_be_uploaded(): void
    {
        Storage::fake('public');

        $response = $this->post('/api/book-types', [
            'BookTypeName' => 'Fiction',
            'BookTypePicture' => UploadedFile::fake()->image('fiction.jpg'),
        ]);

        $response->assertCreated()
            ->assertJsonPath('BookTypeName', 'Fiction');

        $picturePath = $response->json('BookTypePicture');

        $this->assertNotEmpty($picturePath);
        $this->assertStringStartsWith('categorie/', $picturePath);
        Storage::disk('public')->assertExists($picturePath);
    }

    public function test_book_type_picture_must_be_an_image(): void
    {
        Storage::fake('public');

        $response = $this->post('/api/book-types', [
            'BookTypeName' => 'Fiction',
            'BookTypePicture' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('BookTypePicture');
    }
}
