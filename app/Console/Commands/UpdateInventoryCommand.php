<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateInventoryJob;
use App\Services\InventoryService;
use App\Repositories\ItemRepository;

class UpdateInventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update inventory from external API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        UpdateInventoryJob::dispatch(new InventoryService(new ItemRepository()));

        $this->info('Inventory update process has been started.');
    }
}
