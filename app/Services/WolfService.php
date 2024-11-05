<?php

namespace App\Services;

use App\Repositories\ItemRepository;
use App\Services\ItemStrategies\ItemStrategy;

final class WolfService
{
    private ItemStrategy $strategy;
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository, ItemStrategy $strategy)
    {
        $this->itemRepository = $itemRepository;
        $this->strategy = $strategy;
    }

    public function updateQuality(): void
    {
        $items = $this->itemRepository->getAllItems();

        foreach ($items as $item) {
            $this->strategy->update($item);
            $this->itemRepository->save($item);
        }
    }
}
