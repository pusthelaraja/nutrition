@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Product</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                                            <small class="form-text text-muted">Leave empty to auto-generate from name</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Pricing & Inventory</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sale_price" class="form-label">Sale Price</label>
                                                    <input type="number" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku" class="form-label">SKU</label>
                                                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="weight" class="form-label">Weight (kg)</label>
                                                    <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight', $product->weight) }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                                                    <div class="form-text">Leave as 0 for unlimited stock</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-check mt-4">
                                                        <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock" value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="manage_stock">
                                                            <strong>Track Inventory</strong>
                                                        </label>
                                                    </div>
                                                    <div class="form-text">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle"></i>
                                                            Enable to track stock levels and prevent overselling
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-lightbulb me-2"></i>
                                            <strong>Stock Management:</strong>
                                            <ul class="mb-0 mt-2">
                                                <li><strong>Enabled:</strong> System tracks inventory, prevents overselling, shows stock levels</li>
                                                <li><strong>Disabled:</strong> Unlimited stock, no inventory tracking (suitable for digital products/services)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Product Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-select" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="sort_order" class="form-label">Sort Order</label>
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" min="0">
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    Featured
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Current Images</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($product->images()->count() > 0)
                                            <div class="row">
                                                @foreach($product->images()->ordered()->get() as $image)
                                                    <div class="col-6 col-md-4 mb-3">
                                                        <div class="position-relative">
                                                            <img src="{{ $image->image_url }}"
                                                                 alt="{{ $product->name }}"
                                                                 class="img-fluid rounded">
                                                            @if($image->is_featured)
                                                                <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                                                                    <i class="fas fa-star"></i> Featured
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">No images uploaded</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Update Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Featured Image -->
                                        <div class="mb-4">
                                                <x-image-picker
        name="featured_image"
        label="Featured Image"
        placeholder="Click to select featured image"
        :value="$product->featured_image ?? ''"
        class="mb-3"
    />


                                            <div class="alert alert-warning">
                                                <i class="fas fa-star me-2"></i>
                                                <strong>Featured Image:</strong> This will be the main image displayed for your product.
                                            </div>
                                        </div>

                                        <!-- Multiple Images -->
                                        <x-multiple-image-picker
                                            name="images"
                                            label="Additional Images"
                                            :value="$product->images->pluck('image_path')->implode(',')"
                                            class="mb-3"
                                        />

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Tip:</strong> Upload a featured image first, then add additional images. The featured image will be the main product image.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Product Attributes</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="attributes" class="form-label">Attributes (JSON)</label>
                                            <textarea class="form-control" id="attributes" name="attributes" rows="4" placeholder='{"color": "red", "size": "large"}'>{{ old('attributes', $product->attributes ? json_encode($product->attributes, JSON_PRETTY_PRINT) : '') }}</textarea>
                                            <small class="form-text text-muted">Enter attributes in JSON format</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Product
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
