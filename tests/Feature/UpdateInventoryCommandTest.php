<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

use App\Jobs\UpdateInventoryJob;
use App\Services\InventoryService;
use App\Repositories\ItemRepository;

class UpdateInventoryCommandTest extends TestCase
{
    public function testInventoryUpdate(): void
    {
        // Mock API
        Http::fake([
            'https://api.restful-api.dev/objects' => Http::sequence()
                ->push([
                    ['name' => 'Apple AirPods', 'data' => ['quality' => 10, 'sellIn' => 5]], 
                    ['name' => 'Xiaomi Redmi Note 13', 'data' => ['quality' => 20, 'sellIn' => 10]]
                ])
        ]);

        Queue::fake();

        $this->artisan('inventory:update')->assertExitCode(0);

        Queue::assertPushed(UpdateInventoryJob::class, 1);

        // We need to call Job synchronously so we can do assertions...
        $job = (new UpdateInventoryJob((new InventoryService(new ItemRepository()))))->withFakeQueueInteractions();
        $job->handle();

        $this->assertDatabaseHas('items', [
            'name' => 'Apple AirPods',
            'quality' => 10,
            'sellIn' => 5,
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'Xiaomi Redmi Note 13',
            'quality' => 20,
            'sellIn' => 10,
        ]);
    }
}
