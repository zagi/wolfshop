<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\ItemRepository;
use App\Services\ItemStrategies\ItemStrategy;
use App\Services\ItemStrategies\Factory\StrategyFactory;
use App\Models\Item;

final class WolfService
{
    private StrategyFactory $strategyFactory;
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository, StrategyFactory $strategyFactory)
    {
        $this->itemRepository = $itemRepository;
        $this->strategyFactory = $strategyFactory;
    }

    public function updateQuality(): void
    {
        $items = $this->itemRepository->getAllItems();

        foreach ($items as $item) {
            if (!$this->validateItem($item)) {
                Log::warning("Invalid item data for ID {$item->id}, skipping.");
                continue;
            }
            try {
                $strategy = $this->strategyFactory->create($item);
                $dto = $item->toDTO();
                $strategy->update($dto);
                $this->itemRepository->saveDTO($dto);
            } catch (\Exception $e) {
                Log::error("Failed to update item with ID {$item->id}: {$e->getMessage()}");
            }
        }
    }

    private function validateItem(Item $item): bool
    {
        return is_int($item->quality) && is_int($item->sellIn) && $item->quality >= 0 && $item->quality <= 80;
    }
}
