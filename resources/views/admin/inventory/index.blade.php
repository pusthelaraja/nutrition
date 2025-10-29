@extends('layouts.admin')

@section('title', 'Inventory Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Inventory Management</h1>
            <p class="text-muted">Track stock levels, sales analytics, and inventory reports</p>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="month" class="form-select" style="width: auto;">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="form-select" style="width: auto;">
                    @for($i = now()->year - 2; $i <= now()->year + 1; $i++)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-outline-primary">Filter</button>
            </form>
            <a href="{{ route('admin.inventory.export', ['month' => $month, 'year' => $year]) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analytics['total_products'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Sales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($analytics['total_sales_amount'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $analytics['total_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stock Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($analytics['total_stock_value'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Low Stock Alerts -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Low Stock Alerts</h6>
                    <span class="badge bg-warning">{{ count($lowStockAlerts) }}</span>
                </div>
                <div class="card-body">
                    @if(count($lowStockAlerts) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Current Stock</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($lowStockAlerts, 0, 5) as $alert)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $alert['product']->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $alert['product']->sku }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $alert['current_stock'] == 0 ? 'danger' : 'warning' }}">
                                                    {{ $alert['current_stock'] }}
                                                </span>
                                            </td>
                                            <td>{{ $alert['status'] }}</td>
                                            <td>
                                                <a href="{{ route('admin.inventory.show', $alert['product']) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($lowStockAlerts) > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-sm btn-outline-primary">View All Alerts</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">No low stock alerts!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                </div>
                <div class="card-body">
                    @if(count($analytics['top_selling_products']) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['top_selling_products']->take(5) as $product)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $product->product->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $product->product->sku }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $product->stock_sold }}</span>
                                            </td>
                                            <td>₹{{ number_format($product->total_sales_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No sales data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Stock Movements -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Stock Movements</h6>
                </div>
                <div class="card-body">
                    @if($recentMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Movement</th>
                                        <th>Quantity</th>
                                        <th>Previous Stock</th>
                                        <th>New Stock</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMovements as $movement)
                                        <tr>
                                            <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $movement->product->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $movement->product->sku }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $movement->movement_type == 'in' ? 'success' : ($movement->movement_type == 'out' ? 'danger' : 'info') }}">
                                                    {{ ucfirst($movement->movement_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $movement->quantity }}</td>
                                            <td>{{ $movement->previous_stock }}</td>
                                            <td>{{ $movement->new_stock }}</td>
                                            <td>{{ $movement->user->name ?? 'System' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No stock movements recorded</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
