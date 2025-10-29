@props([
    'name' => 'image',
    'value' => '',
    'label' => 'Image',
    'required' => false,
    'placeholder' => 'Click to select image',
    'class' => '',
    'id' => null
])

@php
    $inputId = $id ?? 'image-picker-' . uniqid();
    $previewId = 'image-preview-' . $inputId;
    $buttonId = 'image-button-' . $inputId;

    // Clean the value to handle URL duplication
    $cleanValue = $value;
    if ($value) {
        // Fix duplicated URLs like: /storage/http://localhost:1000/storage/files/...
        if (strpos($value, '/storage/http://') !== false) {
            $parts = explode('/storage/', $value);
            if (count($parts) > 1) {
                $cleanValue = '/storage/' . end($parts);
            }
        } elseif (strpos($value, 'http://') === 0 || strpos($value, 'https://') === 0) {
            // Convert full URL to relative path
            $parsedUrl = parse_url($value);
            $cleanValue = $parsedUrl['path'] ?? $value;
        }

        // Additional cleanup - remove any remaining domain parts
        if (strpos($cleanValue, 'http://') !== false || strpos($cleanValue, 'https://') !== false) {
            $cleanValue = preg_replace('/^https?:\/\/[^\/]+/', '', $cleanValue);
        }
    }
@endphp

<div class="image-picker-component {{ $class }}">
    <label for="{{ $inputId }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="input-group">
        <input type="text"
               name="{{ $name }}"
               id="{{ $inputId }}"
               class="form-control"
               value="{{ $cleanValue }}"
               placeholder="Click Choose to select image"
               readonly
               @if($required) required @endif>

        <button type="button"
                class="btn btn-primary lfm-btn"
                id="{{ $buttonId }}"
                data-input="{{ $inputId }}"
                data-preview="{{ $previewId }}"
                data-type="image">
            <i class="fas fa-folder-open me-1"></i>
            Choose Image
        </button>
    </div>

    <!-- Preview area -->
    <div id="{{ $previewId }}" class="mt-2">
        @if($cleanValue)
            @php
                // Generate proper display URL
                $displayUrl = $cleanValue;
                if (!str_starts_with($cleanValue, 'http://') && !str_starts_with($cleanValue, 'https://')) {
                    $displayUrl = asset($cleanValue);
                }
            @endphp
            <div class="image-preview-container d-flex align-items-start gap-3 p-3 border rounded bg-light">
                <div class="thumbnail-wrapper position-relative">
                    <img src="{{ $displayUrl }}"
                         alt="Preview"
                         class="img-thumbnail shadow-sm"
                         style="width: 120px; height: 120px; object-fit: cover;"
                         onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0zNiAzNkg4NFY4NEgzNlYzNloiIGZpbGw9IiNEOUQ5RDkiLz4KPHA+YXRoIGQ9Ik00MiA0Mkw2MCA2MEw3OCA0Mkw4NCA0OFY3MkgzNlY0OEw0MiA0MloiIGZpbGw9IiNCOUI5QjkiLz4KPHRleHQgeD0iNjAiIHk9IjkwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmaWxsPSIjOTk5IiBmb250LXNpemU9IjEwIj5JbWFnZSBFcnJvcjwvdGV4dD4KPC9zdmc+';">
                    <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                        <button type="button" class="btn btn-light btn-sm" onclick="viewFullImage('{{ $displayUrl }}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="image-info flex-grow-1">
                    <h6 class="mb-2 text-dark">
                        <i class="fas fa-image me-1 text-primary"></i>
                        {{ basename($cleanValue) }}
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-folder me-1"></i>
                            <strong>Stored Path:</strong> {{ $cleanValue }}
                        </small>
                        <small class="text-muted d-block">
                            <i class="fas fa-link me-1"></i>
                            <strong>Display URL:</strong> {{ $displayUrl }}
                        </small>
                    </div>
                    <div class="action-buttons">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm me-2"
                                onclick="viewFullImage('{{ $displayUrl }}')">
                            <i class="fas fa-eye me-1"></i>
                            View Full Size
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm me-2"
                                onclick="copyImageUrl('{{ $displayUrl }}')">
                            <i class="fas fa-copy me-1"></i>
                            Copy URL
                        </button>
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="clearImagePreview('{{ $inputId }}', '{{ $previewId }}')">
                            <i class="fas fa-trash me-1"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.thumbnail-wrapper:hover .thumbnail-overlay {
    opacity: 1 !important;
    transition: opacity 0.3s ease;
}

.thumbnail-overlay {
    transition: opacity 0.3s ease;
}

.image-preview-container {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.image-preview-container:hover {
    border-color: #007bff !important;
    box-shadow: 0 4px 8px rgba(0,123,255,0.15);
}

.action-buttons .btn {
    transition: all 0.2s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.image-picker-component {
    margin-bottom: 1rem;
}
</style>

<script>
// Global callback function that Laravel File Manager expects
function fmSetLink(url, input, preview) {
    console.log('fmSetLink called:', url, input, preview);

    // Clean the URL - remove duplicate storage paths if they exist
    let cleanUrl = url;
    if (url.includes('/storage/') && url.indexOf('/storage/') !== url.lastIndexOf('/storage/')) {
        // Remove duplicate /storage/ paths
        cleanUrl = url.substring(url.lastIndexOf('/storage/') + 9); // Remove everything before the last /storage/ and the /storage/ itself
    } else if (url.startsWith('http://') || url.startsWith('https://')) {
        // If it's a full URL, extract just the path after storage
        const storageIndex = url.indexOf('/storage/');
        if (storageIndex !== -1) {
            cleanUrl = url.substring(storageIndex + 9); // Remove /storage/ prefix
        }
    }

    console.log('Cleaned URL:', cleanUrl);

    const inputElement = document.getElementById(input);
    const previewElement = document.getElementById(preview);

    if (inputElement) {
        inputElement.value = cleanUrl;
        console.log('Set input value to:', cleanUrl);
    }

    if (previewElement) {
        previewElement.innerHTML = `
            <div class="image-preview-container d-flex align-items-start gap-3 p-3 border rounded bg-light">
                <div class="thumbnail-wrapper position-relative">
                    <img src="${cleanUrl}"
                         alt="Preview"
                         class="img-thumbnail shadow-sm"
                         style="width: 120px; height: 120px; object-fit: cover;">
                    <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                        <button type="button" class="btn btn-light btn-sm" onclick="viewFullImage('${cleanUrl}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="image-info flex-grow-1">
                    <h6 class="mb-2 text-dark">
                        <i class="fas fa-image me-1 text-primary"></i>
                        ${cleanUrl.split('/').pop()}
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-folder me-1"></i>
                            Path: ${cleanUrl}
                        </small>
                        <small class="text-muted d-block">
                            <i class="fas fa-link me-1"></i>
                            URL: ${cleanUrl}
                        </small>
                    </div>
                    <div class="action-buttons">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm me-2"
                                onclick="viewFullImage('${cleanUrl}')">
                            <i class="fas fa-eye me-1"></i>
                            View Full Size
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm me-2"
                                onclick="copyImageUrl('${cleanUrl}')">
                            <i class="fas fa-clipboard me-1"></i>
                            Copy URL
                        </button>
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="clearImagePreview('${input}', '${preview}')">
                            <i class="fas fa-trash me-1"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
}

// Utility functions
function viewFullImage(url) {
    window.open(url, '_blank');
}

function copyImageUrl(url) {
    navigator.clipboard.writeText(url).then(function() {
        alert('URL copied to clipboard!');
    });
}

function clearImagePreview(inputId, previewId) {
    document.getElementById(inputId).value = '';
    document.getElementById(previewId).innerHTML = '';
}

// Initialize Laravel File Manager when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if Laravel File Manager is loaded
    if (typeof $ === 'undefined' || typeof $.fn.filemanager === 'undefined') {
        console.error('Laravel File Manager script is not loaded');
        return;
    }

    // Initialize all LFM buttons
    $('.lfm-btn').each(function() {
        const $btn = $(this);
        const type = $btn.data('type');

        console.log('Initializing LFM button for type:', type);

        $btn.filemanager(type, {
            prefix: '/laravel-filemanager'
        });
    });

    console.log('LFM buttons initialized');
});
</script>
