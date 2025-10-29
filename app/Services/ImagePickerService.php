<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImagePickerService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('image-picker');
    }

    /**
     * Process uploaded images for single image picker
     */
    public function processSingleImage($imagePath, $options = [])
    {
        $defaults = $this->config['defaults'];
        $options = array_merge($defaults, $options);

        // Generate thumbnail if needed
        if (isset($options['generate_thumbnail']) && $options['generate_thumbnail']) {
            $this->generateThumbnail($imagePath, $options['thumbnail_size'] ?? 300);
        }

        return $imagePath;
    }

    /**
     * Process uploaded images for multiple image picker
     */
    public function processMultipleImages($imagePaths, $options = [])
    {
        $defaults = $this->config['defaults'];
        $options = array_merge($defaults, $options);

        $processedImages = [];

        foreach ($imagePaths as $imagePath) {
            if (isset($options['generate_thumbnail']) && $options['generate_thumbnail']) {
                $this->generateThumbnail($imagePath, $options['thumbnail_size'] ?? 300);
            }

            $processedImages[] = $imagePath;
        }

        return $processedImages;
    }

    /**
     * Generate thumbnail for an image
     */
    public function generateThumbnail($imagePath, $size = 300)
    {
        try {
            $fullPath = Storage::disk('public')->path($imagePath);
            $pathInfo = pathinfo($fullPath);
            $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

            Image::make($fullPath)
                ->resize($size, $size, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save(Storage::disk('public')->path($thumbnailPath));

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Thumbnail generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete image and its thumbnail
     */
    public function deleteImage($imagePath)
    {
        try {
            // Delete main image
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            // Delete thumbnail if exists
            $pathInfo = pathinfo($imagePath);
            $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get image URL
     */
    public function getImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        return Storage::disk('public')->url($imagePath);
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrl($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }

        // Fallback to original image if thumbnail doesn't exist
        return $this->getImageUrl($imagePath);
    }

    /**
     * Validate image file
     */
    public function validateImage($file, $options = [])
    {
        $defaults = $this->config['defaults'];
        $options = array_merge($defaults, $options);

        $errors = [];

        // Check file size
        if ($file->getSize() > ($options['max_file_size'] * 1024)) {
            $errors[] = str_replace(':size', $options['max_file_size'], $this->config['validation']['file_size_message']);
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $options['allowed_types'])) {
            $errors[] = str_replace(':types', implode(', ', $options['allowed_types']), $this->config['validation']['file_type_message']);
        }

        return $errors;
    }

    /**
     * Get configuration for a specific component
     */
    public function getComponentConfig($componentType)
    {
        return $this->config['components'][$componentType] ?? [];
    }

    /**
     * Get responsive configuration
     */
    public function getResponsiveConfig($breakpoint = 'mobile')
    {
        return $this->config['responsive'][$breakpoint] ?? [];
    }

    /**
     * Get file manager URL with parameters
     */
    public function getFileManagerUrl($type = 'Images', $callback = 'image-picker')
    {
        $baseUrl = $this->config['file_manager']['url'];
        $params = [
            'type' => $type,
            'CKEditor' => $callback,
            'CKEditorFuncNum' => 1,
            'langCode' => 'en'
        ];

        return $baseUrl . '?' . http_build_query($params);
    }
}
