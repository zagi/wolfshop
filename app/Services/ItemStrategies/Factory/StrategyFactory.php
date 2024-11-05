<?php

namespace App\Services\ItemStrategies\Factory;

use Illuminate\Support\Facades\Log;
use App\Services\ItemStrategies\ItemStrategy;
use App\Models\Item;

class StrategyFactory
{
    public function create(Item $item): ItemStrategy
    {
        $itemRules = config('item_rules');

        $rules = $itemRules[$item->name] ?? $itemRules['default'];

        $strategyClass = $rules['strategy_class'] ?? \App\Services\ItemStrategies\DefaultItemStrategy::class;

        if (!class_exists($strategyClass)) {
            Log::error("Strategy class {$strategyClass} for item {$item->name} does not exist.");
            throw new \Exception("Invalid strategy class for item: {$item->name}");
        }

        return new $strategyClass($rules);
    }
}
