<?php

namespace App\Services\ItemStrategies;

use App\DTO\ItemDTO;

class LegendaryItemStrategy implements ItemStrategy
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function update(ItemDTO $item): void
    {
        $item->quality = $this->config['quality_fixed'] ?? 80;
    }
}
