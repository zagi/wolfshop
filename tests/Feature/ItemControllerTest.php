<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Item;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode(env('BASIC_AUTH_USER') . ':' . env('BASIC_AUTH_PASSWORD')),
        ]);
    }

    public function testCanListAllItems()
    {
        Item::factory()->count(3)->create();

        $response = $this->getJson('/api/items');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function testCanCreateAnItem()
    {
        $data = [
            'name' => 'Test Item',
            'sellIn' => 10,
            'quality' => 20,
        ];

        $response = $this->postJson('/api/items', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);

        $this->assertDatabaseHas('items', $data);
    }

    public function testValidatesInputWhenCreatingAnItem()
    {
        $response = $this->postJson('/api/items', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'sellIn', 'quality']);
    }

    public function testCanShowAnItem()
    {
        $item = Item::factory()->create();

        $response = $this->getJson("/api/items/{$item->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $item->name,
            'sellIn' => $item->sellIn,
            'quality' => $item->quality,
        ]);
    }

    public function testReturns404WhenItemNotFound()
    {
        $response = $this->getJson('/api/items/999');

        $response->assertStatus(404);
    }

    public function testCanUpdateAnItem()
    {
        $item = Item::factory()->create();

        $updatedData = [
            'sellIn' => 15,
            'quality' => 25,
        ];

        $response = $this->putJson("/api/items/{$item->id}", $updatedData);

        $response->assertStatus(200);
        $response->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('items', array_merge(['id' => $item->id], $updatedData));
    }

    public function testCanDeleteAnItem()
    {
        $item = Item::factory()->create();

        $response = $this->deleteJson("/api/items/{$item->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Item deleted successfully']);

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    public function testCanUploadAnImageForAnItem()
    {
        $item = Item::factory()->create();
        $file = \Illuminate\Http\UploadedFile::fake()->image('item.jpg');

        $response = $this->postJson("/api/items/{$item->id}/upload-image", [
            'image' => $file,
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($item->fresh()->imgUrl);
    }
}
