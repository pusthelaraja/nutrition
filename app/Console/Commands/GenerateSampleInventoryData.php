<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\InventoryReport;
use App\Services\InventoryService;
use Carbon\Carbon;

class GenerateSampleInventoryData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:generate-sample-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sample inventory data for testing';

    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        parent::__construct();
        $this->inventoryService = $inventoryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sample inventory data...');

        // Get products that manage stock
        $products = Product::where('manage_stock', true)->get();

        if ($products->count() == 0) {
            $this->error('No products found with stock management enabled. Please create some products first.');
            return;
        }

        // Generate sample stock movements for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);

            foreach ($products as $product) {
                // Random stock movements
                if (rand(1, 3) == 1) { // 33% chance
                    $movementType = ['in', 'out', 'adjustment'][rand(0, 2)];
                    $quantity = rand(1, 20);

                    $this->inventoryService->recordStockMovement(
                        $product,
                        $movementType,
                        $quantity,
                        'sample_data',
                        null,
                        "Sample data generation - Day {$i}",
                        1
                    );
                }
            }
        }

        // Generate daily reports for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            $this->inventoryService->generateDailyReport($date);
        }

        $this->info('Sample inventory data generated successfully!');
        $this->info('Generated data for ' . $products->count() . ' products over 30 days.');
    }
}
