<?php

namespace Tests\Feature;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    /** @test */
    public function it_can_list_all_items()
    {
        Item::factory()->count(3)->create();

        $response = $this->getJson('/api/items');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_an_item()
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

    /** @test */
    public function it_validates_input_when_creating_an_item()
    {
        $response = $this->postJson('/api/items', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'sellIn', 'quality']);
    }

    /** @test */
    public function it_can_show_an_item()
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

    /** @test */
    public function it_returns_404_when_item_not_found()
    {
        $response = $this->getJson('/api/items/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_item()
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

    /** @test */
    public function it_can_delete_an_item()
    {
        $item = Item::factory()->create();

        $response = $this->deleteJson("/api/items/{$item->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Item deleted successfully']);

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }

    /** @test */
    public function it_can_upload_an_image_for_an_item()
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
