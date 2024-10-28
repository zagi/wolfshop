<?php

namespace App\Services\ItemStrategies;

use App\Models\Item;

interface ItemStrategy
{
    public function update(Item $item): void;
}
