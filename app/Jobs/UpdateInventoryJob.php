<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Services\InventoryService;

class UpdateInventoryJob implements ShouldQueue
{
    use Queueable;

    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function handle()
    {
        $this->inventoryService->updateInventoryFromApi();
    }
}
