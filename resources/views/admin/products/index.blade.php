@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Products</h3>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="productsTable">
                            <thead>
                                <tr>
                                    <th class="d-none d-md-table-cell">ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th class="d-none d-lg-table-cell">SKU</th>
                                    <th class="d-none d-lg-table-cell">Category</th>
                                    <th>Price</th>
                                    <th class="d-none d-md-table-cell">Stock</th>
                                    <th class="d-none d-sm-table-cell">Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td class="d-none d-md-table-cell">{{ $product->id }}</td>
                                        <td>
                                            @if($product->images && count($product->images) > 0)
                                                <img src="{{ $product->featured_image }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if($product->is_featured)
                                                <span class="badge bg-warning ms-1">Featured</span>
                                            @endif
                                            <div class="d-md-none small text-muted">
                                                @if($product->sku)
                                                    SKU: {{ $product->sku }}
                                                @endif
                                                @if($product->category)
                                                    | {{ $product->category->name }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">{{ $product->sku }}</td>
                                        <td class="d-none d-lg-table-cell">{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <span class="text-decoration-line-through text-muted">₹{{ number_format($product->price, 2) }}</span>
                                                <br>
                                                <span class="text-success fw-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                                            @else
                                                ₹{{ number_format($product->price, 2) }}
                                            @endif
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            @if($product->manage_stock)
                                                <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $product->stock_quantity }} in stock
                                                </span>
                                            @else
                                                <span class="badge bg-info">Unlimited</span>
                                            @endif
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                   class="btn btn-sm btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                                <p>No products found. <a href="{{ route('admin.products.create') }}">Create your first product</a></p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($products->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
