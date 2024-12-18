<?php

namespace App\Services\ItemStrategies;

use Illuminate\Support\Facades\Log;
use App\DTO\ItemDTO;

class ConfigurableItemStrategy implements ItemStrategy
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('item_rules');
    }

    public function update(ItemDTO $item): void
    {
        $rules = $this->config[$item->name] ?? $this->config['default'];

        if (isset($rules['quality_fixed'])) {
            $item->quality = $rules['quality_fixed'];
            return;
        }

        try {
            $this->updateQuality($item, $rules);
        } catch (\Exception $e) {
            Log::error("Error updating item quality '{$item->name}': {$e->getMessage()}");
        }

        if (!($rules['ignore_sellIn'] ?? false)) {
            $item->sellIn--;
        }

        if ($item->sellIn < 0) {
            try {
                $this->applyExpirationRules($item, $rules);
            } catch (\Exception $e) {
                Log::error("Error applying item expiration rules '{$item->name}': {$e->getMessage()}");
            }
        }
    }

    protected function updateQuality(ItemDTO $item, array $rules): void
    {
        $item->quality += $rules['quality_change'];
        if (isset($rules['sellIn_thresholds'])) {
            foreach ($rules['sellIn_thresholds'] as $threshold => $change) {
                if ($item->sellIn <= $threshold) {
                    $item->quality += $change;
                }
            }
        }
        $item->quality = min($rules['max_quality'] ?? 50, $item->quality);
        $item->quality = max($rules['min_quality'] ?? 0, $item->quality);
    }

    protected function applyExpirationRules(ItemDTO $item, array $rules): void
    {
        if (isset($rules['quality_on_expire'])) {
            if (is_int($rules['quality_on_expire'])) {
                $item->quality = $rules['quality_on_expire'];
            } else {
                $item->quality += $rules['quality_on_expire'];
                $item->quality = max($rules['min_quality'] ?? 0, $item->quality);
            }
        }
    }
}
