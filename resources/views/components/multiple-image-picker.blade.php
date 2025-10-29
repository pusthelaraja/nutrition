@props([
    'name' => 'images',
    'label' => 'Additional Images',
    'required' => false,
    'value' => null
])

@php
    $inputId = $name . '_input';
    $previewId = $name . '_preview';
    $buttonId = $name . '_button';

    // Parse existing value (comma-separated paths)
    $existingImages = [];
    if ($value) {
        $images = array_filter(array_map('trim', explode(',', $value)));

        // Clean URLs - remove duplicate storage paths and convert to relative paths
        foreach ($images as $image) {
            $cleanImage = $image;

            // Fix duplicated URLs like: http://localhost:1000/storage/http://localhost:1000/storage/files/...
            if (strpos($image, '/storage/http://') !== false) {
                // Extract the part after the last /storage/
                $parts = explode('/storage/', $image);
                if (count($parts) > 1) {
                    $cleanImage = '/storage/' . end($parts);
                }
            } elseif (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
                // Convert full URL to relative path
                $parsedUrl = parse_url($image);
                $cleanImage = $parsedUrl['path'] ?? $image;
            }

            // Additional cleanup - remove any remaining domain parts
            if (strpos($cleanImage, 'http://') !== false || strpos($cleanImage, 'https://') !== false) {
                $cleanImage = preg_replace('/^https?:\/\/[^\/]+/', '', $cleanImage);
            }

            $existingImages[] = $cleanImage;
        }
    }
@endphp

<div class="mb-3">
    <label for="{{ $inputId }}" class="form-label">
        <i class="fas fa-images me-1"></i>
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="input-group mb-3">
        <input type="text"
               name="{{ $name }}"
               id="{{ $inputId }}"
               class="form-control @error($name) is-invalid @enderror"
               value="{{ old($name, implode(',', $existingImages)) }}"
               placeholder="Click Choose Images to select multiple files"
               readonly
               {{ $required ? 'required' : '' }}>

        <button type="button"
                class="btn btn-primary lfm-multiple-btn"
                id="{{ $buttonId }}"
                data-input="{{ $inputId }}"
                data-preview="{{ $previewId }}"
                data-type="image">
            <i class="fas fa-images me-1"></i>
            Choose Images
        </button>
    </div>

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <!-- Preview area -->
    <div id="{{ $previewId }}" class="multiple-images-preview">
        @if(count($existingImages) > 0)
            <div class="row g-3">
                @foreach($existingImages as $index => $imagePath)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <div class="image-item position-relative">
                            <img src="{{ $imagePath }}"
                                 alt="Preview {{ $index + 1 }}"
                                 class="img-thumbnail w-100"
                                 style="height: 100px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIGZpbGw9IiNEOUQ5RDkiLz4KPHA+YXRoIGQ9Ik0zNSAzNUw1MCA1MEw2NSAzNUw3MCA0MFY2NUgzMFY0MEwzNSAzNVoiIGZpbGw9IiNCOUI5QjkiLz4KPHRleHQgeD0iNTAiIHk9Ijc1IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjgiPkltYWdlIEVycm9yPC90ZXh0Pgo8L3N2Zz4=';">
                            <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-light" onclick="viewFullImage('{{ $imagePath }}')" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-light" onclick="copyImageUrl('{{ $imagePath }}')" title="Copy">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button type="button" class="btn btn-light" onclick="removeImageFromMultiple('{{ $inputId }}', '{{ $previewId }}', {{ $index }})" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@once
@push('styles')
<style>
.multiple-images-preview {
    min-height: 50px;
}

.image-item {
    transition: all 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.image-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.image-item:hover .image-overlay {
    opacity: 1 !important;
    transition: opacity 0.3s ease;
}

.image-overlay {
    transition: opacity 0.3s ease;
}

.image-overlay .btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

       /* Responsive adjustments */
       @media (max-width: 1200px) {
           .image-item img {
               height: 90px !important;
           }
       }

       @media (max-width: 992px) {
           .image-item img {
               height: 85px !important;
           }
       }

       @media (max-width: 768px) {
           .image-item img {
               height: 80px !important;
           }

           .image-overlay .btn-group .btn {
               padding: 0.2rem 0.4rem;
               font-size: 0.7rem;
           }
       }

       @media (max-width: 576px) {
           .image-item img {
               height: 70px !important;
           }

           .image-overlay .btn-group .btn {
               padding: 0.15rem 0.3rem;
               font-size: 0.65rem;
           }
       }

       @media (max-width: 480px) {
           .image-item img {
               height: 60px !important;
           }

           .image-overlay .btn-group .btn {
               padding: 0.1rem 0.25rem;
               font-size: 0.6rem;
           }
       }
</style>
@endpush

@push('scripts')
<script>
    // Function to remove image from multiple selection
    window.removeImageFromMultiple = function(inputId, previewId, index) {
        console.log('Removing image at index:', index);

        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (input && preview) {
            // Get current images
            const currentImages = input.value ? input.value.split(',').map(img => img.trim()).filter(img => img) : [];

            // Remove image at index
            currentImages.splice(index, 1);

            // Update input value
            input.value = currentImages.join(',');

            // Re-render preview
            renderMultipleImagePreview(previewId, currentImages);

            console.log('Updated images:', currentImages);
        }
    };

    // Function to render multiple image preview
    window.renderMultipleImagePreview = function(previewId, images) {
        const preview = document.getElementById(previewId);
        if (!preview) return;

        if (images.length === 0) {
            preview.innerHTML = '';
            return;
        }

        let html = '<div class="row g-3">';

        images.forEach((imagePath, index) => {
            // Clean the image path first
            let cleanPath = imagePath;

            // Fix duplicated URLs
            if (imagePath.includes('/storage/http://') || imagePath.includes('/storage/https://')) {
                const parts = imagePath.split('/storage/');
                if (parts.length > 1) {
                    cleanPath = '/storage/' + parts[parts.length - 1];
                }
            } else if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
                // Convert full URL to relative path
                try {
                    const url = new URL(imagePath);
                    cleanPath = url.pathname;
                } catch (e) {
                    // If URL parsing fails, try regex
                    cleanPath = imagePath.replace(/^https?:\/\/[^\/]+/, '');
                }
            }

            // Convert relative path to full URL for display
            let displayUrl = cleanPath;
            if (!cleanPath.startsWith('http://') && !cleanPath.startsWith('https://')) {
                displayUrl = window.location.origin + cleanPath;
            }

            html += `
                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="image-item position-relative">
                        <img src="${displayUrl}"
                             alt="Preview ${index + 1}"
                             class="img-thumbnail w-100"
                             style="height: 100px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0zMCAzMEg3MFY3MEgzMFYzMFoiIGZpbGw9IiNEOUQ5RDkiLz4KPHA+YXRoIGQ9Ik0zNSAzNUw1MCA1MEw2NSAzNUw3MCA0MFY2NUgzMFY0MEwzNSAzNVoiIGZpbGw9IiNCOUI5QjkiLz4KPHRleHQgeD0iNTAiIHk9Ijc1IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjgiPkltYWdlIEVycm9yPC90ZXh0Pgo8L3N2Zz4='">
                        <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-light" onclick="viewFullImage('${displayUrl}')" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-light" onclick="copyImageUrl('${displayUrl}')" title="Copy">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button type="button" class="btn btn-light" onclick="removeImageFromMultiple('${previewId.replace('_preview', '_input')}', '${previewId}', ${index})" title="Remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        preview.innerHTML = html;
    };
</script>
@endpush
@endonce
