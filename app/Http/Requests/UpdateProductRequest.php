<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            // Basic Information
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|min:3',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId . '|regex:/^[a-z0-9\-]+$/',
            'description' => 'nullable|string|max:5000',
            'short_description' => 'nullable|string|max:500',

            // Pricing
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'sale_price' => 'nullable|numeric|min:0|max:999999.99|lt:price',

            // Inventory
            'sku' => 'required|string|max:100|unique:products,sku,' . $productId . '|regex:/^[A-Z0-9\-_]+$/',
            'stock_quantity' => 'nullable|integer|min:0|max:999999',
            'weight' => 'nullable|numeric|min:0|max:999.99',

            // Images
            'featured_image' => 'nullable|string|max:500',
            'images' => 'nullable|string|max:5000',

            // Attributes
            'attributes' => 'nullable|string|max:10000',

            // Status
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999'
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'name.required' => 'Product name is required.',
            'name.min' => 'Product name must be at least 3 characters.',
            'name.max' => 'Product name cannot exceed 255 characters.',
            'slug.unique' => 'This slug is already taken.',
            'slug.regex' => 'Slug can only contain lowercase letters, numbers, and hyphens.',
            'price.required' => 'Product price is required.',
            'price.min' => 'Price must be at least 0.01.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'sale_price.lt' => 'Sale price must be less than regular price.',
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU is already taken.',
            'sku.regex' => 'SKU can only contain uppercase letters, numbers, hyphens, and underscores.',
            'stock_quantity.min' => 'Stock quantity cannot be negative.',
            'stock_quantity.max' => 'Stock quantity cannot exceed 999,999.',
            'weight.min' => 'Weight cannot be negative.',
            'weight.max' => 'Weight cannot exceed 999.99 kg.',
            'images.max' => 'Images data is too large.',
            'attributes.max' => 'Attributes data is too large.',
            'sort_order.max' => 'Sort order cannot exceed 9999.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'name' => 'product name',
            'slug' => 'URL slug',
            'description' => 'product description',
            'short_description' => 'short description',
            'price' => 'product price',
            'sale_price' => 'sale price',
            'sku' => 'SKU',
            'stock_quantity' => 'stock quantity',
            'weight' => 'product weight',
            'featured_image' => 'featured image',
            'images' => 'product images',
            'attributes' => 'product attributes',
            'is_active' => 'active status',
            'is_featured' => 'featured status',
            'sort_order' => 'sort order'
        ];
    }
}
