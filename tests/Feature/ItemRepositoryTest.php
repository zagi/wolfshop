<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Repositories\ItemRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private ItemRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ItemRepository();
    }

    public function testCanCreateItem()
    {
        $data = [
            'name' => 'Apple AirPods',
            'quality' => 10,
            'sellIn' => 5,
        ];

        $item = $this->repository->create($data);
        $this->assertDatabaseHas('items', ['name' => 'Apple AirPods']);
        $this->assertEquals($data['name'], $item->name);
        $this->assertEquals($data['quality'], $item->quality);
        $this->assertEquals($data['sellIn'], $item->sellIn);
    }

    public function testCanFindItemByName()
    {
        Item::factory()->create(['name' => 'Samsung Galaxy S23']);
        $item = $this->repository->findByName('Samsung Galaxy S23');
        $this->assertNotNull($item);
        $this->assertEquals('Samsung Galaxy S23', $item->name);
    }

    public function testCanUpdateItem()
    {
        $item = Item::factory()->create(['name' => 'Apple iPad Air', 'quality' => 10]);
        $item->quality = 15;
        $this->repository->save($item);

        $this->assertDatabaseHas('items', ['name' => 'Apple iPad Air', 'quality' => 15]);
    }
}