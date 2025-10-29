<div class="results-info">
    <h6 class="mb-0">
        Showing {{ $products->count() }} of {{ $products->total() }} products
    </h6>
    @if(request()->hasAny(['category', 'price_range', 'featured', 'sale', 'in_stock', 'sort']))
        <small class="text-muted">Filtered results</small>
    @endif

    <!-- Debug: Show active filters -->
    @if(config('app.debug'))
        <div class="mt-2">
            <small class="text-info">
                <strong>Active Filters:</strong>
                @if(request('category'))
                    Category: {{ request('category') }} |
                @endif
                @if(request('price_range'))
                    Price: {{ request('price_range') }} |
                @endif
                @if(request('featured'))
                    Featured |
                @endif
                @if(request('sale'))
                    Sale |
                @endif
                @if(request('in_stock'))
                    In Stock |
                @endif
                @if(request('sort'))
                    Sort: {{ request('sort') }}
                @endif
            </small>
        </div>
    @endif
</div>
