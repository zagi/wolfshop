<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

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

        $this->artisan('inventory:update')->assertExitCode(0);

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
