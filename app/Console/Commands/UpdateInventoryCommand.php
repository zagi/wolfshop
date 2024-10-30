<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Item;
use App\Repositories\ItemRepository;

class UpdateInventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update inventory from external API';

    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        parent::__construct();
        $this->itemRepository = $itemRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $response = Http::get('https://api.restful-api.dev/objects');

        if ($response->failed()) {
            $this->error('Failed to fetch data from the API.');
        }

        $itemsData = $response->json();

        foreach ($itemsData as $itemData) {
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

        $this->info('Inventory updated successfully.');
    }
}
