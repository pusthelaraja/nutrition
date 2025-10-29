@extends('layouts.admin')

@section('title', 'View Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Product Details</h3>
                    <div>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Name:</strong> {{ $product->name }}</p>
                                            <p><strong>Slug:</strong> {{ $product->slug }}</p>
                                            <p><strong>SKU:</strong> {{ $product->sku ?: 'N/A' }}</p>
                                            <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong>
                                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </p>
                                            <p><strong>Featured:</strong>
                                                <span class="badge {{ $product->is_featured ? 'bg-warning' : 'bg-light text-dark' }}">
                                                    {{ $product->is_featured ? 'Yes' : 'No' }}
                                                </span>
                                            </p>
                                            <p><strong>Sort Order:</strong> {{ $product->sort_order }}</p>
                                        </div>
                                    </div>

                                    @if($product->description)
                                        <div class="mt-3">
                                            <strong>Description:</strong>
                                            <div class="mt-2">{!! nl2br(e($product->description)) !!}</div>
                                        </div>
                                    @endif

                                    @if($product->short_description)
                                        <div class="mt-3">
                                            <strong>Short Description:</strong>
                                            <div class="mt-2">{!! nl2br(e($product->short_description)) !!}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>Pricing & Inventory</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Price:</strong> ₹{{ number_format($product->price, 2) }}</p>
                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                <p><strong>Sale Price:</strong> ₹{{ number_format($product->sale_price, 2) }}</p>
                                                <p><strong>Discount:</strong> {{ number_format((($product->price - $product->sale_price) / $product->price) * 100, 1) }}%</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Weight:</strong> {{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</p>
                                            <p><strong>Stock Management:</strong> {{ $product->manage_stock ? 'Yes' : 'No' }}</p>
                                            @if($product->manage_stock)
                                                <p><strong>Stock Quantity:</strong> {{ $product->stock_quantity }}</p>
                                                <p><strong>In Stock:</strong>
                                                    <span class="badge {{ $product->in_stock ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $product->in_stock ? 'Yes' : 'No' }}
                                                    </span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($product->attributes)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Product Attributes</h5>
                                    </div>
                                    <div class="card-body">
                                        <pre class="bg-light p-3 rounded">{{ json_encode($product->attributes, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Product Images</h5>
                                </div>
                                <div class="card-body">
                                    @if($product->images && count($product->images) > 0)
                                        <div class="row">
                                            @foreach($product->images as $image)
                                                <div class="col-6 mb-3">
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                         alt="{{ $product->name }}"
                                                         class="img-fluid rounded">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-image fa-3x mb-3"></i>
                                            <p>No images uploaded</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5>Timestamps</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i:s') }}</p>
                                    <p><strong>Updated:</strong> {{ $product->updated_at->format('M d, Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
