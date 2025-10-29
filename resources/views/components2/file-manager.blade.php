@props([
    'name' => 'image',
    'label' => 'Image',
    'value' => null,
    'multiple' => false,
    'accept' => 'image/*',
    'maxSize' => '2048', // KB
    'maxFiles' => 1,
    'required' => false,
    'help' => 'Supported formats: JPG, PNG, GIF, WebP. Max size: 2MB',
    'preview' => true,
    'gallery' => false,
    'folder' => 'uploads'
])

<div class="file-manager-component" data-name="{{ $name }}">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <!-- File Input -->
    <div class="file-upload-area">
        <input type="file"
               class="form-control file-input @error($name) is-invalid @enderror"
               id="{{ $name }}"
               name="{{ $multiple ? $name.'[]' : $name }}"
               accept="{{ $accept }}"
               {{ $multiple ? 'multiple' : '' }}
               {{ $required ? 'required' : '' }}
               data-max-size="{{ $maxSize }}"
               data-max-files="{{ $maxFiles }}"
               style="display: none;">

        <!-- Upload Zone -->
        <div class="upload-zone" onclick="document.getElementById('{{ $name }}').click()">
            <div class="upload-content">
                <div class="upload-icon">
                    <i class="bi bi-cloud-upload fs-1 text-primary"></i>
                </div>
                <div class="upload-text">
                    <h6 class="mb-1">Click to upload {{ $multiple ? 'files' : 'file' }}</h6>
                    <p class="text-muted mb-0">or drag and drop {{ $multiple ? 'files' : 'file' }} here</p>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="upload-progress" style="display: none;">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted">Uploading...</small>
        </div>
    </div>

    @if($help)
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            {{ $help }}
        </small>
    @endif

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <!-- Preview Area -->
    @if($preview)
        <div class="preview-area mt-3" style="{{ !$value ? 'display: none;' : '' }}">
            <h6 class="preview-title">
                <i class="bi bi-eye me-1"></i>
                Preview {{ $multiple ? '(s)' : '' }}
            </h6>
            <div class="preview-container">
                @if($value)
                    @if($multiple && is_array($value))
                        @foreach($value as $index => $image)
                            <div class="preview-item" data-index="{{ $index }}">
                                <div class="preview-image">
                                    <img src="{{ Storage::url($image) }}" alt="Preview">
                                    <div class="preview-overlay">
                                        <button type="button" class="btn btn-sm btn-danger remove-image"
                                                data-index="{{ $index }}" title="Remove">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info view-image"
                                                data-src="{{ Storage::url($image) }}" title="View Full Size">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="preview-name">{{ basename($image) }}</small>
                            </div>
                        @endforeach
                    @elseif($value)
                        <div class="preview-item" data-index="0">
                            <div class="preview-image">
                                <img src="{{ Storage::url($value) }}" alt="Preview">
                                <div class="preview-overlay">
                                    <button type="button" class="btn btn-sm btn-danger remove-image"
                                            data-index="0" title="Remove">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info view-image"
                                            data-src="{{ Storage::url($value) }}" title="View Full Size">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="preview-name">{{ basename($value) }}</small>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    <!-- Hidden inputs for existing files -->
    <div class="existing-files">
        @if($value)
            @if($multiple && is_array($value))
                @foreach($value as $index => $image)
                    <input type="hidden" name="existing_{{ $name }}[]" value="{{ $image }}" data-index="{{ $index }}">
                @endforeach
            @elseif($value)
                <input type="hidden" name="existing_{{ $name }}" value="{{ $value }}" data-index="0">
            @endif
        @endif
    </div>

    @if($gallery)
        <!-- Gallery Modal -->
        <div class="modal fade" id="galleryModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Browse Gallery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="gallery-grid">
                            <!-- Gallery items will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Image View Modal -->
<div class="modal fade" id="imageViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Full Size" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.file-manager-component {
    margin-bottom: 1rem;
}

.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-zone:hover {
    border-color: #0d6efd;
    background: #e7f1ff;
    transform: translateY(-2px);
}

.upload-zone.dragover {
    border-color: #0d6efd;
    background: #e7f1ff;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.upload-icon {
    opacity: 0.7;
}

.upload-text h6 {
    color: #495057;
    font-weight: 600;
}

.upload-progress {
    margin-top: 1rem;
}

.preview-area {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1rem;
    background: #fff;
}

.preview-title {
    color: #495057;
    margin-bottom: 1rem;
    font-weight: 600;
}

.preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.preview-item {
    text-align: center;
}

.preview-image {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
    aspect-ratio: 1;
    background: #f8f9fa;
}

.preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.preview-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.preview-image:hover .preview-overlay {
    opacity: 1;
}

.preview-image:hover img {
    transform: scale(1.1);
}

.preview-name {
    display: block;
    margin-top: 0.5rem;
    color: #6c757d;
    word-break: break-all;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 1rem;
    max-height: 400px;
    overflow-y: auto;
}

.gallery-item {
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 0.375rem;
    overflow: hidden;
    transition: border-color 0.3s ease;
}

.gallery-item:hover {
    border-color: #0d6efd;
}

.gallery-item.selected {
    border-color: #198754;
}

.gallery-item img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
}

@media (max-width: 576px) {
    .preview-container {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }

    .upload-zone {
        padding: 1.5rem 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize file manager for all components
    $('.file-manager-component').each(function() {
        initFileManager($(this));
    });
});

function initFileManager($component) {
    const $fileInput = $component.find('.file-input');
    const $uploadZone = $component.find('.upload-zone');
    const $previewArea = $component.find('.preview-area');
    const $previewContainer = $component.find('.preview-container');
    const $progressBar = $component.find('.upload-progress');

    const name = $component.data('name');
    const multiple = $fileInput.prop('multiple');
    const maxSize = parseInt($fileInput.data('max-size')) * 1024; // Convert to bytes
    const maxFiles = parseInt($fileInput.data('max-files'));

    // File input change handler
    $fileInput.on('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag and drop handlers
    $uploadZone.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $uploadZone.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $uploadZone.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    // Remove image handler
    $component.on('click', '.remove-image', function() {
        const index = $(this).data('index');
        removeImage(index);
    });

    // View image handler
    $component.on('click', '.view-image', function() {
        const src = $(this).data('src');
        showImageModal(src);
    });

    function handleFiles(files) {
        if (!files.length) return;

        // Validate file count
        if (!multiple && files.length > 1) {
            showAlert('Only one file is allowed.', 'warning');
            return;
        }

        if (multiple && files.length > maxFiles) {
            showAlert(`Maximum ${maxFiles} files allowed.`, 'warning');
            return;
        }

        // Validate and process files
        const validFiles = [];
        for (let file of files) {
            if (validateFile(file)) {
                validFiles.push(file);
            }
        }

        if (validFiles.length > 0) {
            processFiles(validFiles);
        }
    }

    function validateFile(file) {
        // Check file type
        if (!file.type.startsWith('image/')) {
            showAlert(`${file.name} is not a valid image file.`, 'error');
            return false;
        }

        // Check file size
        if (file.size > maxSize) {
            const maxSizeMB = (maxSize / (1024 * 1024)).toFixed(2);
            showAlert(`${file.name} is too large. Maximum size is ${maxSizeMB}MB.`, 'error');
            return false;
        }

        return true;
    }

    function processFiles(files) {
        $progressBar.show();
        const formData = new FormData();

        files.forEach((file, index) => {
            formData.append(`files[${index}]`, file);
        });

        // Simulate upload with progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);

                // Process files for preview
                files.forEach(file => {
                    addPreview(file);
                });

                $progressBar.hide();
                $previewArea.show();
                showAlert('Files uploaded successfully!', 'success');
            }

            $progressBar.find('.progress-bar').css('width', progress + '%');
        }, 200);
    }

    function addPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const index = $previewContainer.children().length;
            const previewHtml = `
                <div class="preview-item" data-index="${index}">
                    <div class="preview-image">
                        <img src="${e.target.result}" alt="Preview">
                        <div class="preview-overlay">
                            <button type="button" class="btn btn-sm btn-danger remove-image"
                                    data-index="${index}" title="Remove">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-info view-image"
                                    data-src="${e.target.result}" title="View Full Size">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <small class="preview-name">${file.name}</small>
                </div>
            `;

            if (!multiple) {
                $previewContainer.html(previewHtml);
            } else {
                $previewContainer.append(previewHtml);
            }
        };
        reader.readAsDataURL(file);
    }

    function removeImage(index) {
        const $item = $component.find(`.preview-item[data-index="${index}"]`);
        $item.fadeOut(300, function() {
            $(this).remove();

            // Remove corresponding hidden input
            $component.find(`input[data-index="${index}"]`).remove();

            // Hide preview area if no items left
            if ($previewContainer.children().length === 0) {
                $previewArea.hide();
            }

            showAlert('Image removed successfully.', 'success');
        });
    }

    function showImageModal(src) {
        $('#imageViewModal img').attr('src', src);
        $('#imageViewModal').modal('show');
    }
}

function showAlert(message, type) {
    const alertClass = type === 'error' ? 'danger' : type;
    const iconClass = type === 'success' ? 'check-circle' :
                     type === 'warning' ? 'exclamation-triangle' :
                     type === 'error' ? 'x-circle' : 'info-circle';

    const alertHtml = `
        <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
            <i class="bi bi-${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Find the nearest container or use body
    const $container = $('.container-fluid').first();
    if ($container.length) {
        $container.prepend(alertHtml);
    } else {
        $('body').prepend(alertHtml);
    }

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').first().alert('close');
    }, 5000);
}
</script>
@endpush
