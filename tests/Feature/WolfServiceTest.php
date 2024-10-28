<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Services\WolfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WolfServiceTest extends TestCase
{
    use RefreshDatabase;

    private WolfService $wolfService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wolfService = new WolfService(new ItemRepository());
    }

    public function testNormalItemDegradesQuality()
    {
        Item::factory()->create(['name' => 'Normal Item', 'quality' => 10, 'sellIn' => 5]);
        $this->wolfService->updateQuality();

        $item = Item::first();
        $this->assertEquals(9, $item->quality);
        $this->assertEquals(4, $item->sellIn);
    }

    public function testAppleAirPodsIncreasesQualityOverTime()
    {
        Item::factory()->create(['name' => 'Apple AirPods', 'quality' => 10, 'sellIn' => 5]);
        $this->wolfService->updateQuality();

        $item = Item::first();
        $this->assertEquals(11, $item->quality);
        $this->assertEquals(4, $item->sellIn);
    }

    public function testQualityNeverExceedsFifty()
    {
        Item::factory()->create(['name' => 'Apple AirPods', 'quality' => 50, 'sellIn' => 5]);
        $this->wolfService->updateQuality();

        $item = Item::first();
        $this->assertEquals(50, $item->quality);
    }

    public function testSamsungGalaxyS23QualityRemainsConstant()
    {
        Item::factory()->create(['name' => 'Samsung Galaxy S23', 'quality' => 80, 'sellIn' => 5]);
        $this->wolfService->updateQuality();

        $item = Item::first();
        $this->assertEquals(80, $item->quality);
        $this->assertEquals(5, $item->sellIn); 
    }
}