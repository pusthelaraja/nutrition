@props([
    'name' => 'image',
    'label' => 'Upload File',
    'type' => 'image', // 'image' or 'file'
    'required' => false,
    'multiple' => false,
    'value' => null,
    'helpText' => null,
    'accept' => null,
    'maxSize' => '5MB',
    'previewSize' => '150px'
])

@php
    $inputId = $name . '_' . uniqid();
    $previewId = $inputId . '_preview';
    $buttonId = $inputId . '_button';
    $clearId = $inputId . '_clear';

    // Determine file manager type and accept attributes
    $lfmType = $type === 'image' ? 'image' : 'file';
    $acceptAttr = $accept ?: ($type === 'image' ? 'image/*' : '*');

    // Handle multiple values (for existing files)
    $currentFiles = [];
    if ($value) {
        if (is_string($value)) {
            $currentFiles = $multiple ? json_decode($value, true) ?: [] : [$value];
        } elseif (is_array($value)) {
            $currentFiles = $value;
        }
    }
@endphp

<div class="mb-3">
    <label for="{{ $inputId }}" class="form-label">
        <i class="bi bi-{{ $type === 'image' ? 'image' : 'file-earmark' }} me-1"></i>
        {{ $label }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    <div class="file-upload-container" data-type="{{ $lfmType }}" data-multiple="{{ $multiple ? 'true' : 'false' }}">
        <!-- Hidden input to store the selected file path(s) -->
        <input type="hidden"
               name="{{ $name }}"
               id="{{ $inputId }}"
               value="{{ $multiple ? json_encode($currentFiles) : ($currentFiles[0] ?? '') }}"
               {{ $required ? 'required' : '' }}>

        <!-- Upload button -->
        <div class="input-group">
            <button type="button"
                    class="btn btn-outline-primary lfm-button"
                    id="{{ $buttonId }}"
                    data-input="{{ $inputId }}"
                    data-preview="{{ $previewId }}"
                    data-type="{{ $lfmType }}"
                    data-multiple="{{ $multiple ? 'true' : 'false' }}">
                <i class="bi bi-cloud-upload me-2"></i>
                {{ $multiple ? 'Choose Files' : 'Choose File' }}
            </button>

            @if(!$required || !empty($currentFiles))
                <button type="button"
                        class="btn btn-outline-danger"
                        id="{{ $clearId }}"
                        onclick="clearFileSelection('{{ $inputId }}', '{{ $previewId }}')">
                    <i class="bi bi-trash"></i>
                </button>
            @endif
        </div>

        <!-- File info and constraints -->
        <small class="text-muted d-block mt-1">
            <i class="bi bi-info-circle me-1"></i>
            {{ $helpText ?: "Max size: {$maxSize}. " . ($type === 'image' ? 'Supported: JPG, PNG, GIF' : 'All file types allowed') }}
            @if($multiple)
                <br><strong>Multiple files:</strong> Hold Ctrl/Cmd to select multiple files
            @endif
        </small>

        <!-- File preview area -->
        <div id="{{ $previewId }}" class="file-preview-area mt-3">
            @if(!empty($currentFiles))
                @foreach($currentFiles as $index => $file)
                    @if($type === 'image' && $file)
                        <div class="preview-item d-inline-block me-2 mb-2 position-relative">
                            <img src="{{ asset('storage/' . $file) }}"
                                 alt="Preview"
                                 class="img-thumbnail"
                                 style="width: {{ $previewSize }}; height: {{ $previewSize }}; object-fit: cover;">
                            @if($multiple)
                                <button type="button"
                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-1"
                                        onclick="removeFileFromMultiple('{{ $inputId }}', {{ $index }})"
                                        style="transform: translate(50%, -50%);">
                                    <i class="bi bi-x" style="font-size: 0.7rem;"></i>
                                </button>
                            @endif
                        </div>
                    @elseif($file)
                        <div class="preview-item d-inline-block me-2 mb-2">
                            <div class="file-preview-item p-2 border rounded bg-light d-flex align-items-center">
                                <i class="bi bi-file-earmark me-2 text-primary"></i>
                                <span class="small">{{ basename($file) }}</span>
                                @if($multiple)
                                    <button type="button"
                                            class="btn btn-danger btn-sm ms-2 p-1"
                                            onclick="removeFileFromMultiple('{{ $inputId }}', {{ $index }})">
                                        <i class="bi bi-x" style="font-size: 0.7rem;"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>

    @error($name)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@push('styles')
<style>
.file-upload-container {
    position: relative;
}

.file-preview-area {
    min-height: 40px;
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.file-preview-area:not(:empty) {
    border-color: #198754;
    background-color: #f0f9f4;
}

.file-preview-area.drag-over {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}

.preview-item {
    transition: all 0.3s ease;
}

.preview-item:hover {
    transform: scale(1.05);
}

.file-preview-item {
    max-width: 200px;
    word-break: break-all;
}

.lfm-button {
    position: relative;
    overflow: hidden;
}

.lfm-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.upload-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #0d6efd, #20c997);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.upload-progress.active {
    animation: uploadProgress 2s ease-in-out;
}

@keyframes uploadProgress {
    0% { transform: scaleX(0); }
    50% { transform: scaleX(0.7); }
    100% { transform: scaleX(1); }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Laravel File Manager for all LFM buttons
    $('.lfm-button').each(function() {
        const button = $(this);
        const type = button.data('type');
        const inputId = button.data('input');
        const previewId = button.data('preview');
        const multiple = button.data('multiple') === 'true';

        console.log('Initializing LFM button:', button.attr('id'), 'Type:', type, 'Multiple:', multiple);

        button.filemanager(type, {
            prefix: '/laravel-filemanager'
        });
    });

    // Handle file selection callback - this must be global
    window.fmSetLink = function($url, $input, $preview, $multiple = false) {
        const inputElement = document.getElementById($input);
        const previewElement = document.getElementById($preview);

        if ($multiple) {
            // Handle multiple file selection
            let currentFiles = [];
            try {
                currentFiles = JSON.parse(inputElement.value || '[]');
            } catch (e) {
                currentFiles = [];
            }

            // Add new file(s) to the array
            if (Array.isArray($url)) {
                currentFiles = currentFiles.concat($url);
            } else {
                currentFiles.push($url);
            }

            inputElement.value = JSON.stringify(currentFiles);
            updatePreview($input, $preview, currentFiles, true);
        } else {
            // Handle single file selection
            inputElement.value = $url;
            updatePreview($input, $preview, [$url], false);
        }

        // Show upload animation
        showUploadProgress($input);
    }

    };
});

function updatePreview(inputId, previewId, files, isMultiple) {
    const previewElement = document.getElementById(previewId);
    const container = previewElement.closest('.file-upload-container');
    const type = container.dataset.type;

    previewElement.innerHTML = '';

    if (files && files.length > 0) {
        files.forEach((file, index) => {
            if (file) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item d-inline-block me-2 mb-2 position-relative';

                if (type === 'image') {
                    // Image preview
                    previewItem.innerHTML = `
                        <img src="/storage/${file}"
                             alt="Preview"
                             class="img-thumbnail"
                             style="width: 150px; height: 150px; object-fit: cover;">
                        ${isMultiple ? `
                        <button type="button"
                                class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-1"
                                onclick="removeFileFromMultiple('${inputId}', ${index})"
                                style="transform: translate(50%, -50%);">
                            <i class="bi bi-x" style="font-size: 0.7rem;"></i>
                        </button>` : ''}
                    `;
                } else {
                    // File preview
                    previewItem.innerHTML = `
                        <div class="file-preview-item p-2 border rounded bg-light d-flex align-items-center">
                            <i class="bi bi-file-earmark me-2 text-primary"></i>
                            <span class="small">${file.split('/').pop()}</span>
                            ${isMultiple ? `
                            <button type="button"
                                    class="btn btn-danger btn-sm ms-2 p-1"
                                    onclick="removeFileFromMultiple('${inputId}', ${index})">
                                <i class="bi bi-x" style="font-size: 0.7rem;"></i>
                            </button>` : ''}
                        </div>
                    `;
                }

                previewElement.appendChild(previewItem);
            }
        });
    }
}

function clearFileSelection(inputId, previewId) {
    const inputElement = document.getElementById(inputId);
    const previewElement = document.getElementById(previewId);

    inputElement.value = '';
    previewElement.innerHTML = '';

    // Show clear animation
    showClearAnimation(previewId);
}

function removeFileFromMultiple(inputId, index) {
    const inputElement = document.getElementById(inputId);
    const previewId = inputId + '_preview';

    let files = [];
    try {
        files = JSON.parse(inputElement.value || '[]');
    } catch (e) {
        files = [];
    }

    // Remove file at index
    files.splice(index, 1);

    inputElement.value = JSON.stringify(files);
    updatePreview(inputId, previewId, files, true);
}

function showUploadProgress(inputId) {
    const button = document.getElementById(inputId + '_button');
    const progressBar = document.createElement('div');
    progressBar.className = 'upload-progress active';
    button.appendChild(progressBar);

    setTimeout(() => {
        if (progressBar.parentNode) {
            progressBar.parentNode.removeChild(progressBar);
        }
    }, 2000);
}

function showClearAnimation(previewId) {
    const previewElement = document.getElementById(previewId);
    previewElement.style.opacity = '0.5';
    previewElement.style.transform = 'scale(0.95)';

    setTimeout(() => {
        previewElement.style.opacity = '1';
        previewElement.style.transform = 'scale(1)';
    }, 300);
}

// Add drag and drop functionality
document.querySelectorAll('.file-preview-area').forEach(area => {
    area.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });

    area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
    });

    area.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');

        const container = this.closest('.file-upload-container');
        const button = container.querySelector('.lfm-button');

        // Trigger file manager instead of handling drop directly
        // as we want to use the file manager for consistency
        button.click();
    });
});
</script>
@endpush
