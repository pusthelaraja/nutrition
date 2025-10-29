<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use App\Models\Product;
use App\Models\InventoryReport;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Display inventory dashboard.
     */
    public function index(Request $request)
    {
        try {
            $month = $request->get('month', now()->month);
            $year = $request->get('year', now()->year);

            // Get monthly analytics
            $analytics = $this->inventoryService->getMonthlyAnalytics($month, $year);

            // Get low stock alerts
            $lowStockAlerts = $this->inventoryService->getLowStockAlerts();

            // Get recent stock movements
            $recentMovements = StockMovement::with(['product', 'user'])
                ->latest()
                ->limit(10)
                ->get();

            return view('admin.inventory.index', compact(
                'analytics',
                'lowStockAlerts',
                'recentMovements',
                'month',
                'year'
            ));

        } catch (\Exception $e) {
            \Log::error('Inventory dashboard failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load inventory dashboard. Please try again.');
        }
    }

    /**
     * Display product inventory details.
     */
    public function show(Product $product, Request $request)
    {
        try {
            $month = $request->get('month', now()->month);
            $year = $request->get('year', now()->year);

            // Get product analytics
            $analytics = $this->inventoryService->getProductAnalytics($product, $month, $year);

            // Get stock movements for the product
            $stockMovements = StockMovement::where('product_id', $product->id)
                ->with('user')
                ->latest()
                ->paginate(20);

            // Get monthly reports for the product
            $monthlyReports = InventoryReport::where('product_id', $product->id)
                ->orderBy('report_date', 'desc')
                ->limit(12)
                ->get();

            return view('admin.inventory.show', compact(
                'product',
                'analytics',
                'stockMovements',
                'monthlyReports',
                'month',
                'year'
            ));

        } catch (\Exception $e) {
            \Log::error('Product inventory details failed: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to load product inventory details. Please try again.');
        }
    }

    /**
     * Adjust stock manually.
     */
    public function adjustStock(Request $request, Product $product)
    {
        try {
            $request->validate([
                'new_quantity' => 'required|integer|min:0',
                'reason' => 'nullable|string|max:500'
            ]);

            $this->inventoryService->adjustStock(
                $product,
                $request->new_quantity,
                $request->reason,
                auth()->id()
            );

            return redirect()->back()
                ->with('success', 'Stock adjusted successfully.');

        } catch (\Exception $e) {
            \Log::error('Stock adjustment failed: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to adjust stock. Please try again.');
        }
    }

    /**
     * Generate inventory reports.
     */
    public function generateReports(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'action' => 'required|in:generate,regenerate'
            ]);

            $date = Carbon::parse($request->date);

            if ($request->action === 'regenerate') {
                // Delete existing reports for the date
                InventoryReport::whereDate('report_date', $date)->delete();
            }

            // Generate reports
            $this->inventoryService->generateDailyReport($date);

            return redirect()->back()
                ->with('success', 'Inventory reports generated successfully.');

        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to generate reports. Please try again.');
        }
    }

    /**
     * Export inventory data.
     */
    public function export(Request $request)
    {
        try {
            $month = $request->get('month', now()->month);
            $year = $request->get('year', now()->year);
            $format = $request->get('format', 'csv');

            $reports = InventoryReport::with('product')
                ->month($month, $year)
                ->get();

            // Generate export data
            $exportData = $reports->map(function ($report) {
                return [
                    'Product Name' => $report->product->name,
                    'SKU' => $report->product->sku,
                    'Category' => $report->product->category->name,
                    'Opening Stock' => $report->opening_stock,
                    'Closing Stock' => $report->closing_stock,
                    'Stock In' => $report->stock_in,
                    'Stock Out' => $report->stock_out,
                    'Stock Sold' => $report->stock_sold,
                    'Total Sales Amount' => $report->total_sales_amount,
                    'Total Orders' => $report->total_orders,
                    'Report Date' => $report->report_date->format('Y-m-d')
                ];
            });

            if ($format === 'csv') {
                return $this->exportToCsv($exportData, "inventory_report_{$month}_{$year}.csv");
            }

            return redirect()->back()
                ->with('error', 'Unsupported export format.');

        } catch (\Exception $e) {
            \Log::error('Inventory export failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return redirect()->back()
                ->with('error', 'Failed to export inventory data. Please try again.');
        }
    }

    /**
     * Export data to CSV.
     */
    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Write headers
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
            }

            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
