<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'report_date',
        'opening_stock',
        'closing_stock',
        'stock_in',
        'stock_out',
        'stock_sold',
        'stock_returned',
        'stock_adjusted',
        'total_sales_amount',
        'total_orders'
    ];

    protected $casts = [
        'report_date' => 'date',
        'opening_stock' => 'integer',
        'closing_stock' => 'integer',
        'stock_in' => 'integer',
        'stock_out' => 'integer',
        'stock_sold' => 'integer',
        'stock_returned' => 'integer',
        'stock_adjusted' => 'integer',
        'total_sales_amount' => 'decimal:2',
        'total_orders' => 'integer'
    ];

    /**
     * Get the product that owns the inventory report.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    /**
     * Scope for current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('report_date', now()->month)
                    ->whereYear('report_date', now()->year);
    }

    /**
     * Scope for specific month.
     */
    public function scopeMonth($query, $month, $year)
    {
        return $query->whereMonth('report_date', $month)
                    ->whereYear('report_date', $year);
    }

    /**
     * Get net stock movement.
     */
    public function getNetStockMovementAttribute()
    {
        return $this->stock_in - $this->stock_out;
    }

    /**
     * Get stock turnover rate.
     */
    public function getStockTurnoverRateAttribute()
    {
        if ($this->opening_stock == 0) {
            return 0;
        }

        return round(($this->stock_sold / $this->opening_stock) * 100, 2);
    }
}
