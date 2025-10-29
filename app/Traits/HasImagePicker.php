<?php

namespace App\Traits;

use App\Services\ImagePickerService;

trait HasImagePicker
{
    protected $imagePickerService;

    public function __construct()
    {
        $this->imagePickerService = new ImagePickerService();
    }

    /**
     * Process single image upload
     */
    protected function processImage($request, $fieldName = 'image', $options = [])
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);

        // Validate image
        $errors = $this->imagePickerService->validateImage($file, $options);
        if (!empty($errors)) {
            throw new \Exception(implode(', ', $errors));
        }

        // Store image
        $path = $file->store('images', 'public');

        // Process image
        return $this->imagePickerService->processSingleImage($path, $options);
    }

    /**
     * Process multiple images upload
     */
    protected function processImages($request, $fieldName = 'images', $options = [])
    {
        if (!$request->hasFile($fieldName)) {
            return [];
        }

        $files = $request->file($fieldName);
        $processedImages = [];

        foreach ($files as $file) {
            // Validate image
            $errors = $this->imagePickerService->validateImage($file, $options);
            if (!empty($errors)) {
                continue; // Skip invalid files
            }

            // Store image
            $path = $file->store('images', 'public');

            // Process image
            $processedImages[] = $this->imagePickerService->processSingleImage($path, $options);
        }

        return $processedImages;
    }

    /**
     * Delete image and its thumbnail
     */
    protected function deleteImage($imagePath)
    {
        return $this->imagePickerService->deleteImage($imagePath);
    }

    /**
     * Get image URL
     */
    protected function getImageUrl($imagePath)
    {
        return $this->imagePickerService->getImageUrl($imagePath);
    }

    /**
     * Get thumbnail URL
     */
    protected function getThumbnailUrl($imagePath)
    {
        return $this->imagePickerService->getThumbnailUrl($imagePath);
    }

    /**
     * Merge existing images with new ones
     */
    protected function mergeImages($existingImages, $newImages, $maxImages = 10)
    {
        if (is_string($existingImages)) {
            $existingImages = json_decode($existingImages, true) ?? [];
        }

        if (is_string($newImages)) {
            $newImages = json_decode($newImages, true) ?? [];
        }

        $mergedImages = array_merge($existingImages, $newImages);

        // Remove duplicates
        $mergedImages = array_unique($mergedImages);

        // Limit to max images
        return array_slice($mergedImages, 0, $maxImages);
    }

    /**
     * Get image picker configuration
     */
    protected function getImagePickerConfig($componentType = 'single_image')
    {
        return $this->imagePickerService->getComponentConfig($componentType);
    }
}
