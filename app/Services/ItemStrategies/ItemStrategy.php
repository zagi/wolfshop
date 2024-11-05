<?php

namespace App\Services\ItemStrategies;

use App\DTO\ItemDTO;

interface ItemStrategy
{
    public function update(ItemDTO $item): void;
}
