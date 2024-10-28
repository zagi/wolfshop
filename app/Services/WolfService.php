<?php

namespace App\Services;

use App\Repositories\ItemRepository;
use App\Services\ItemStrategies\ConfigurableItemStrategy;

final class WolfService
{
    private ConfigurableItemStrategy $strategy;

    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->strategy = new ConfigurableItemStrategy();
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
