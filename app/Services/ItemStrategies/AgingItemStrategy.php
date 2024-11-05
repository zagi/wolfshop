<?php

namespace App\Services\ItemStrategies;

use App\DTO\ItemDTO;
use Illuminate\Support\Facades\Log;

class AgingItemStrategy implements ItemStrategy
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function update(ItemDTO $item): void
    {
        try {
            $item->quality += $this->config['quality_increase'] ?? 1;
            $item->quality = min($this->config['max_quality'] ?? 50, $item->quality);
            $item->sellIn--;
        } catch (\Exception $e) {
            Log::error("Error updating item quality '{$item->name}': {$e->getMessage()}");
        }
    }
}
