<?php

namespace App\Services\ItemStrategies;

use App\DTO\ItemDTO;
use Illuminate\Support\Facades\Log;

class ConcertItemStrategy implements ItemStrategy
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

            foreach ($this->config['sellIn_thresholds'] as $threshold => $increase) {
                if ($item->sellIn <= $threshold) {
                    $item->quality += $increase;
                }
            }

            $item->quality = min($this->config['max_quality'] ?? 50, $item->quality);

            if ($item->sellIn <= 0) {
                $item->quality = $this->config['quality_on_expire'] ?? 0;
            }

            $item->sellIn--;
        } catch (\Exception $e) {
            Log::error("Error updating item quality '{$item->name}': {$e->getMessage()}");
        }
    }
}
