@extends('layouts.admin')

@section('title', 'View Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Category Details</h3>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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
                                            <p><strong>Name:</strong> {{ $category->name }}</p>
                                            <p><strong>Slug:</strong> {{ $category->slug }}</p>
                                            <p><strong>Products:</strong> {{ $category->products_count ?? 0 }} products</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status:</strong>
                                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </p>
                                            <p><strong>Sort Order:</strong> {{ $category->sort_order }}</p>
                                        </div>
                                    </div>

                                    @if($category->description)
                                        <div class="mt-3">
                                            <strong>Description:</strong>
                                            <div class="mt-2">{!! nl2br(e($category->description)) !!}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($category->products && $category->products->count() > 0)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Products in this Category</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>SKU</th>
                                                        <th>Price</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($category->products as $product)
                                                        <tr>
                                                            <td>{{ $product->name }}</td>
                                                            <td>{{ $product->sku ?: 'N/A' }}</td>
                                                            <td>₹{{ number_format($product->price, 2) }}</td>
                                                            <td>
                                                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                                   class="btn btn-sm btn-outline-info" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Category Image</h5>
                                </div>
                            <div class="card-body">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="img-fluid rounded">
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>No image uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Timestamps</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Created:</strong> {{ $category->created_at->format('M d, Y H:i:s') }}</p>
                                <p><strong>Updated:</strong> {{ $category->updated_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
