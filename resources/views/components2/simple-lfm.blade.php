    @props([
    'name' => 'image',
    'label' => 'Upload File',
    'type' => 'image', // 'image' or 'file'
    'required' => false,
    'value' => null
])

@php
    $inputId = $name . '_input';
    $previewId = $name . '_preview';
    $buttonId = $name . '_button';
@endphp

<div class="mb-3">
    <label for="{{ $inputId }}" class="form-label">
        <i class="bi bi-{{ $type === 'image' ? 'image' : 'file-earmark' }} me-1"></i>
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="input-group">
        <input type="text"
               name="{{ $name }}"
               id="{{ $inputId }}"
               class="form-control @error($name) is-invalid @enderror"
               value="{{ old($name, $value) }}"
               placeholder="Click Choose to select file"
               readonly
               {{ $required ? 'required' : '' }}>

        <button type="button"
                class="btn btn-primary lfm-btn"
                id="{{ $buttonId }}"
                data-input="{{ $inputId }}"
                data-preview="{{ $previewId }}"
                data-type="{{ $type }}">
            <i class="bi bi-folder2-open me-1"></i>
            Choose {{ ucfirst($type) }}
        </button>
    </div>

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <!-- Preview area -->
    <div id="{{ $previewId }}" class="mt-2">
        @if($value && $type === 'image')
            <div class="image-preview-container d-flex align-items-start gap-3 p-3 border rounded bg-light">
                <div class="thumbnail-wrapper position-relative">
                        <img src="{{ $value }}"
                         alt="Preview"
                         class="img-thumbnail shadow-sm"
                         style="width: 120px; height: 120px; object-fit: cover;">
                    <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                        <button type="button" class="btn btn-light btn-sm" onclick="viewFullImage('{{ $value }}')">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="image-info flex-grow-1">
                    <h6 class="mb-2 text-dark">
                        <i class="bi bi-image me-1 text-primary"></i>
                        {{ basename($value) }}
                    </h6>
                    <div class="mb-2">
                        <small class="text-muted d-block">
                            <i class="bi bi-folder me-1"></i>
                            Path: {{ $value }}
                        </small>
                        <small class="text-muted d-block">
                            <i class="bi bi-link-45deg me-1"></i>
                            URL: {{ $value }}
                        </small>
                    </div>
                    <div class="action-buttons">
                        <button type="button"
                                class="btn btn-outline-primary btn-sm me-2"
                                onclick="viewFullImage('{{ $value }}')">
                            <i class="bi bi-eye me-1"></i>
                            View Full Size
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary btn-sm me-2"
                                onclick="copyImageUrl('{{ $value }}')">
                            <i class="bi bi-clipboard me-1"></i>
                            Copy URL
                        </button>
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="clearImagePreview('{{ $inputId }}', '{{ $previewId }}')">
                            <i class="bi bi-trash me-1"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        @elseif($value)
            <div class="file-preview-container d-flex align-items-center gap-3 p-3 border rounded bg-light">
                <div class="file-icon">
                    <i class="bi bi-file-earmark-text text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <div class="file-info flex-grow-1">
                    <h6 class="mb-1">
                        <i class="bi bi-file-earmark me-1"></i>
                        {{ basename($value) }}
                    </h6>
                    <small class="text-muted d-block">{{ $value }}</small>
                    <div class="action-buttons mt-2">
                        <a href="{{ $value }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-download me-1"></i>
                            Download
                        </a>
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="clearImagePreview('{{ $inputId }}', '{{ $previewId }}')">
                            <i class="bi bi-trash me-1"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@once
@push('styles')
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
    border-color: #0d6efd !important;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
}

.file-preview-container {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6 !important;
}

.file-preview-container:hover {
    border-color: #198754 !important;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
}

.action-buttons .btn {
    transition: all 0.2s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
}

/* Toast positioning */
.toast-container {
    z-index: 1060;
}

/* Modal image styling */
#imageViewModal .modal-body img {
    transition: transform 0.3s ease;
}

#imageViewModal .modal-body img:hover {
    transform: scale(1.02);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Simple LFM component loaded');

    // Check if required dependencies are available
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded');
        return;
    }

    if (typeof $.fn.filemanager === 'undefined') {
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
        // Determine if it's an image based on the data-type
        const button = document.querySelector(`[data-input="${input}"]`);
        const type = button ? button.getAttribute('data-type') : 'file';

        if (type === 'image') {
            previewElement.innerHTML = `
                <div class="image-preview-container d-flex align-items-start gap-3 p-3 border rounded bg-light">
                    <div class="thumbnail-wrapper position-relative">
                        <img src="${cleanUrl}"
                             alt="Preview"
                             class="img-thumbnail shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                            <button type="button" class="btn btn-light btn-sm" onclick="viewFullImage('${cleanUrl}')">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="image-info flex-grow-1">
                        <h6 class="mb-2 text-dark">
                            <i class="bi bi-image me-1 text-primary"></i>
                            ${cleanUrl.split('/').pop()}
                        </h6>
                        <div class="mb-2">
                            <small class="text-muted d-block">
                                <i class="bi bi-folder me-1"></i>
                                Path: ${cleanUrl}
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-link-45deg me-1"></i>
                                URL: ${cleanUrl}
                            </small>
                        </div>
                        <div class="action-buttons">
                            <button type="button"
                                    class="btn btn-outline-primary btn-sm me-2"
                                    onclick="viewFullImage('${cleanUrl}')">
                                <i class="bi bi-eye me-1"></i>
                                View Full Size
                            </button>
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm me-2"
                                    onclick="copyImageUrl('${cleanUrl}')">
                                <i class="bi bi-clipboard me-1"></i>
                                Copy URL
                            </button>
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="clearImagePreview('${input}', '${preview}')">
                                <i class="bi bi-trash me-1"></i>
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            previewElement.innerHTML = `
                <div class="file-preview-container d-flex align-items-center gap-3 p-3 border rounded bg-light">
                    <div class="file-icon">
                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="file-info flex-grow-1">
                        <h6 class="mb-1">
                            <i class="bi bi-file-earmark me-1"></i>
                            ${cleanUrl.split('/').pop()}
                        </h6>
                        <small class="text-muted d-block">${cleanUrl}</small>
                        <div class="action-buttons mt-2">
                            <a href="${cleanUrl}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm me-2">
                                <i class="bi bi-download me-1"></i>
                                Download
                            </a>
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm"
                                    onclick="clearImagePreview('${input}', '${preview}')">
                                <i class="bi bi-trash me-1"></i>
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
        console.log('Updated preview for:', url);
    }
}

// Make it globally available
window.fmSetLink = fmSetLink;

// Utility functions for image preview functionality
window.viewFullImage = function(imageUrl) {
    // Create modal to view full-size image
    const modal = `
        <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="imageViewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageViewModalLabel">
                            <i class="bi bi-image me-2"></i>
                            Full Size Image
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageUrl}"
                             alt="Full Size Image"
                             class="img-fluid rounded shadow"
                             style="max-height: 70vh; width: auto;">
                        <div class="mt-3">
                            <small class="text-muted">${imageUrl}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="${imageUrl}"
                           target="_blank"
                           class="btn btn-outline-primary">
                            <i class="bi bi-box-arrow-up-right me-1"></i>
                            Open in New Tab
                        </a>
                        <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="copyImageUrl('${imageUrl}')">
                            <i class="bi bi-clipboard me-1"></i>
                            Copy URL
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('imageViewModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modal);

    // Show modal
    const modalElement = new bootstrap.Modal(document.getElementById('imageViewModal'));
    modalElement.show();

    // Remove modal from DOM when hidden
    document.getElementById('imageViewModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
};

window.copyImageUrl = function(url) {
    navigator.clipboard.writeText(url).then(function() {
        // Show success feedback
        showToast('URL copied to clipboard!', 'success');
    }, function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('URL copied to clipboard!', 'success');
    });
};

window.clearImagePreview = function(inputId, previewId) {
    const inputElement = document.getElementById(inputId);
    const previewElement = document.getElementById(previewId);

    if (inputElement) {
        inputElement.value = '';
    }

    if (previewElement) {
        previewElement.innerHTML = '';
    }

    showToast('Image removed successfully!', 'info');
};

// Toast notification function
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    const toastId = 'toast-' + Date.now();

    const toast = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toast);

    const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
        autohide: true,
        delay: 3000
    });
    toastElement.show();

    // Remove toast element after it's hidden
    document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1060';
    document.body.appendChild(container);
    return container;
}
</script>
@endpush
@endonce
