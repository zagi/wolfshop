<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use App\Repositories\ItemRepository;

class InventoryService
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function updateInventoryFromApi(): void
    {
        $response = Http::get('https://api.restful-api.dev/objects');

        if ($response->failed()) {
            Log::error('Failed to fetch data from the API.');
            return;
        }

        $itemsData = $response->json();

        foreach ($itemsData as $itemData) {
            $this->updateOrCreateItem($itemData);
        }

        Log::info('Inventory updated successfully.');
    }

    private function updateOrCreateItem(array $itemData): void
    {
        $item = Item::where('name', '=', $itemData['name'])->first();

        if ($item) {
            $item->quality = min(50, $item->quality + $itemData['data']['quality']);
        } else {
            $item = new Item();
            $item->name = $itemData['name'];
            $item->quality = min(50, $itemData['data']['quality']);
            $item->sellIn = $itemData['data']['sellIn'];
        }

        $this->itemRepository->save($item);
    }
}
