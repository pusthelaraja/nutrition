<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\InventoryReport;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Record stock movement.
     */
    public function recordStockMovement(
        Product $product,
        string $movementType,
        int $quantity,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null,
        ?int $userId = null
    ): StockMovement {
        $previousStock = $product->stock_quantity;
        $newStock = $this->calculateNewStock($previousStock, $quantity, $movementType);

        // Update product stock
        $product->update(['stock_quantity' => $newStock]);

        // Record movement
        return StockMovement::create([
            'product_id' => $product->id,
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'user_id' => $userId
        ]);
    }

    /**
     * Calculate new stock based on movement type.
     */
    private function calculateNewStock(int $currentStock, int $quantity, string $movementType): int
    {
        return match ($movementType) {
            'in', 'return' => $currentStock + $quantity,
            'out' => $currentStock - $quantity,
            'adjustment' => $quantity, // Direct adjustment
            default => $currentStock
        };
    }

    /**
     * Process order stock deduction.
     */
    public function processOrderStock(Order $order): void
    {
        foreach ($order->orderItems as $item) {
            if ($item->product->manage_stock) {
                $this->recordStockMovement(
                    $item->product,
                    'out',
                    $item->quantity,
                    'order',
                    $order->id,
                    "Order #{$order->order_number}",
                    $order->user_id
                );
            }
        }
    }

    /**
     * Process stock return.
     */
    public function processStockReturn(Order $order): void
    {
        foreach ($order->orderItems as $item) {
            if ($item->product->manage_stock) {
                $this->recordStockMovement(
                    $item->product,
                    'return',
                    $item->quantity,
                    'order',
                    $order->id,
                    "Return for Order #{$order->order_number}",
                    $order->user_id
                );
            }
        }
    }

    /**
     * Generate daily inventory reports.
     */
    public function generateDailyReport(Carbon $date = null): void
    {
        $date = $date ?? Carbon::today();
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        $products = Product::where('manage_stock', true)->get();

        foreach ($products as $product) {
            $this->generateProductDailyReport($product, $date);
        }
    }

    /**
     * Generate daily report for specific product.
     */
    private function generateProductDailyReport(Product $product, Carbon $date): void
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Get opening stock (previous day's closing stock)
        $previousReport = InventoryReport::where('product_id', $product->id)
            ->where('report_date', $date->copy()->subDay())
            ->first();

        $openingStock = $previousReport ? $previousReport->closing_stock : $product->stock_quantity;

        // Get movements for the day
        $movements = StockMovement::where('product_id', $product->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->get();

        $stockIn = $movements->where('movement_type', 'in')->sum('quantity');
        $stockOut = $movements->where('movement_type', 'out')->sum('quantity');
        $stockReturned = $movements->where('movement_type', 'return')->sum('quantity');
        $stockAdjusted = $movements->where('movement_type', 'adjustment')->sum('quantity');

        $closingStock = $openingStock + $stockIn - $stockOut + $stockReturned + $stockAdjusted;

        // Get sales data
        $salesData = $this->getProductSalesData($product, $startOfDay, $endOfDay);

        // Create or update report
        InventoryReport::updateOrCreate(
            [
                'product_id' => $product->id,
                'report_date' => $date->toDateString()
            ],
            [
                'opening_stock' => $openingStock,
                'closing_stock' => $closingStock,
                'stock_in' => $stockIn,
                'stock_out' => $stockOut,
                'stock_sold' => $salesData['quantity_sold'],
                'stock_returned' => $stockReturned,
                'stock_adjusted' => $stockAdjusted,
                'total_sales_amount' => $salesData['total_amount'],
                'total_orders' => $salesData['total_orders']
            ]
        );
    }

    /**
     * Get product sales data for date range.
     */
    private function getProductSalesData(Product $product, Carbon $startDate, Carbon $endDate): array
    {
        $salesData = OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->where('status', '!=', 'cancelled');
            })
            ->selectRaw('
                SUM(quantity) as quantity_sold,
                SUM(total_price) as total_amount,
                COUNT(DISTINCT order_id) as total_orders
            ')
            ->first();

        return [
            'quantity_sold' => $salesData->quantity_sold ?? 0,
            'total_amount' => $salesData->total_amount ?? 0,
            'total_orders' => $salesData->total_orders ?? 0
        ];
    }

    /**
     * Get monthly inventory analytics.
     */
    public function getMonthlyAnalytics(int $month = null, int $year = null): array
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $reports = InventoryReport::with('product')
            ->month($month, $year)
            ->get();

        $topSellingProducts = $reports->sortByDesc('stock_sold')->take(10)->values();
        $lowStockProducts = $reports->where('closing_stock', '<', 10)->values();
        $zeroStockProducts = $reports->where('closing_stock', 0)->values();

        return [
            'total_products' => $reports->count(),
            'total_stock_value' => $reports->sum(function ($report) {
                return $report->closing_stock * $report->product->price;
            }),
            'total_sales_amount' => $reports->sum('total_sales_amount'),
            'total_orders' => $reports->sum('total_orders'),
            'top_selling_products' => $topSellingProducts,
            'low_stock_products' => $lowStockProducts,
            'zero_stock_products' => $zeroStockProducts
        ];
    }

    /**
     * Get product performance analytics.
     */
    public function getProductAnalytics(Product $product, int $month = null, int $year = null): array
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $report = InventoryReport::where('product_id', $product->id)
            ->month($month, $year)
            ->first();

        if (!$report) {
            return [
                'product' => $product,
                'month' => $month,
                'year' => $year,
                'opening_stock' => 0,
                'closing_stock' => $product->stock_quantity,
                'stock_sold' => 0,
                'total_sales_amount' => 0,
                'total_orders' => 0,
                'stock_turnover_rate' => 0
            ];
        }

        return [
            'product' => $product,
            'month' => $month,
            'year' => $year,
            'opening_stock' => $report->opening_stock,
            'closing_stock' => $report->closing_stock,
            'stock_sold' => $report->stock_sold,
            'total_sales_amount' => $report->total_sales_amount,
            'total_orders' => $report->total_orders,
            'stock_turnover_rate' => $report->stock_turnover_rate,
            'net_movement' => $report->net_stock_movement
        ];
    }

    /**
     * Get low stock alerts.
     */
    public function getLowStockAlerts(int $threshold = 10): array
    {
        return Product::where('manage_stock', true)
            ->where('stock_quantity', '<=', $threshold)
            ->where('is_active', true)
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'product' => $product,
                    'current_stock' => $product->stock_quantity,
                    'category' => $product->category->name,
                    'status' => $product->stock_quantity == 0 ? 'Out of Stock' : 'Low Stock'
                ];
            })
            ->toArray();
    }

    /**
     * Adjust stock manually.
     */
    public function adjustStock(
        Product $product,
        int $newQuantity,
        string $reason = null,
        ?int $userId = null
    ): StockMovement {
        $currentStock = $product->stock_quantity;
        $adjustment = $newQuantity - $currentStock;

        return $this->recordStockMovement(
            $product,
            'adjustment',
            $newQuantity,
            'adjustment',
            null,
            $reason ?? 'Manual stock adjustment',
            $userId
        );
    }
}
