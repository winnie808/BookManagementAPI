<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthorPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_photo_can_be_uploaded(): void
    {
        Storage::fake('public');

        $response = $this->post('/api/authors', [
            'AuthorName' => 'Jane Doe',
            'Photo' => UploadedFile::fake()->image('jane.jpg'),
        ]);

        $response->assertCreated()
            ->assertJsonPath('AuthorName', 'Jane Doe');

        $photoPath = $response->json('Photo');

        $this->assertNotEmpty($photoPath);
        $this->assertStringStartsWith('categorie/', $photoPath);
        Storage::disk('public')->assertExists($photoPath);
    }

    public function test_author_photo_must_be_an_image(): void
    {
        Storage::fake('public');

        $response = $this->post('/api/authors', [
            'AuthorName' => 'Jane Doe',
            'Photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors('Photo');
    }
}
