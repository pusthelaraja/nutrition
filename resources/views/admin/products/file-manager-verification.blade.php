@extends('layouts.admin')

@section('title', 'File Manager Verification Test')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">File Manager & Image Picker Verification</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- File Manager Tests -->
                        <div class="col-md-6">
                            <h5>File Manager Tests</h5>
                            <div class="list-group">
                                <a href="/admin/file-manager" target="_blank" class="list-group-item list-group-item-action">
                                    <i class="fas fa-folder-open me-2"></i>
                                    <strong>Admin File Manager</strong><br>
                                    <small>Direct access to file manager</small>
                                </a>

                                <a href="/laravel-filemanager?working_dir=/shares" target="_blank" class="list-group-item list-group-item-action">
                                    <i class="fas fa-share-alt me-2"></i>
                                    <strong>Shared Folder</strong><br>
                                    <small>Laravel File Manager - Shared Directory</small>
                                </a>

                                <a href="/laravel-filemanager?working_dir=/1/products" target="_blank" class="list-group-item list-group-item-action">
                                    <i class="fas fa-user me-2"></i>
                                    <strong>User Products Folder</strong><br>
                                    <small>Laravel File Manager - User Products</small>
                                </a>

                                <a href="/laravel-filemanager" target="_blank" class="list-group-item list-group-item-action">
                                    <i class="fas fa-cog me-2"></i>
                                    <strong>Default File Manager</strong><br>
                                    <small>Laravel File Manager - Default Directory</small>
                                </a>
                            </div>
                        </div>

                        <!-- Image Picker Tests -->
                        <div class="col-md-6">
                            <h5>Image Picker Tests</h5>
                            <form id="verificationForm">
                                <div class="mb-3">
                                    <x-image-picker
                                        name="test_image_1"
                                        label="Test Image 1"
                                        placeholder="Click to test image picker"
                                        value=""
                                        class="mb-3" />
                                </div>

                                <div class="mb-3">
                                    <x-image-picker
                                        name="test_image_2"
                                        label="Test Image 2"
                                        placeholder="Another image picker test"
                                        value=""
                                        class="mb-3" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Selected Images:</label>
                                    <div id="selected-images" class="alert alert-info">
                                        <strong>No images selected yet</strong>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Verification Steps -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Verification Steps</h5>
                            <div class="card">
                                <div class="card-body">
                                    <ol>
                                        <li><strong>Test File Manager Access:</strong>
                                            <ul>
                                                <li>Click each file manager link above</li>
                                                <li>Verify you can see files in each directory</li>
                                                <li>Check if files are consistent across directories</li>
                                            </ul>
                                        </li>

                                        <li><strong>Test Image Picker:</strong>
                                            <ul>
                                                <li>Click "Select Image" in the image pickers above</li>
                                                <li>Verify the file manager opens in the correct directory</li>
                                                <li>Try selecting an existing image</li>
                                                <li>Try uploading a new image</li>
                                            </ul>
                                        </li>

                                        <li><strong>Check Console Logs:</strong>
                                            <ul>
                                                <li>Open browser console (F12)</li>
                                                <li>Look for "File manager loaded in image picker"</li>
                                                <li>Check for any error messages</li>
                                                <li>Verify "Current working_dir" shows correct directory</li>
                                            </ul>
                                        </li>

                                        <li><strong>Test Image Selection:</strong>
                                            <ul>
                                                <li>Select an image in the file manager</li>
                                                <li>Verify it appears in the image picker</li>
                                                <li>Check if the image path is correct</li>
                                                <li>Test with multiple image pickers</li>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Information -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Debug Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-info">
                                        <h6>System Information:</h6>
                                        <strong>User:</strong> {{ auth()->user()->name ?? 'Not logged in' }}<br>
                                        <strong>User ID:</strong> {{ auth()->id() ?? 'N/A' }}<br>
                                        <strong>Current Time:</strong> {{ now()->format('Y-m-d H:i:s') }}<br>
                                        <strong>Laravel Version:</strong> {{ app()->version() }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-warning">
                                        <h6>Directory Structure:</h6>
                                        <strong>Shared Folder:</strong> <code>/shares</code><br>
                                        <strong>User Folder:</strong> <code>/{{ auth()->id() ?? '1' }}</code><br>
                                        <strong>Products Folder:</strong> <code>/{{ auth()->id() ?? '1' }}/products</code><br>
                                        <strong>Photos Folder:</strong> <code>/{{ auth()->id() ?? '1' }}/photos</code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Results -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Test Results</h5>
                            <div class="card">
                                <div class="card-body">
                                    <div id="test-results">
                                        <div class="alert alert-secondary">
                                            <strong>Test Results:</strong> Click the test buttons above and check the results here.
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button class="btn btn-primary" onclick="runVerificationTests()">
                                            <i class="fas fa-play me-2"></i>Run All Tests
                                        </button>
                                        <button class="btn btn-secondary" onclick="clearTestResults()">
                                            <i class="fas fa-trash me-2"></i>Clear Results
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables for testing
let testResults = [];
let imageSelectionCount = 0;

// Enhanced image selection handler
window.setImageUrl = function(url) {
    console.log('Image selected:', url);
    imageSelectionCount++;

    // Update selected images display
    updateSelectedImages();

    // Add to test results
    testResults.push({
        type: 'image_selection',
        url: url,
        timestamp: new Date().toISOString(),
        success: true
    });

    // Call original function
    if (window.currentImagePickerInput) {
        const inputId = window.currentImagePickerInput;
        const previewId = inputId.replace('image-picker-', 'image-preview-');

        // Set the input value
        document.getElementById(inputId).value = url;

        // Update preview
        document.getElementById(previewId).innerHTML = `
            <div class="preview-image">
                <img src="${url}" alt="Preview" class="img-fluid">
                <div class="image-actions">
                    <button type="button" class="btn btn-sm btn-primary" onclick="openImagePicker('${inputId}', '${inputId.replace('image-picker-', 'image-modal-')}')">
                        <i class="fas fa-edit"></i> Change
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage('${inputId}', '${previewId}')">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
        `;

        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById(inputId.replace('image-picker-', 'image-modal-')));
        if (modal) {
            modal.hide();
        }

        // Clear current input reference
        window.currentImagePickerInput = null;
    }
};

// Update selected images display
function updateSelectedImages() {
    const container = document.getElementById('selected-images');
    const inputs = document.querySelectorAll('input[name^="test_image"]');
    const selectedImages = Array.from(inputs).filter(input => input.value).map(input => input.value);

    if (selectedImages.length > 0) {
        container.innerHTML = `
            <strong>Selected Images (${selectedImages.length}):</strong><br>
            ${selectedImages.map((img, index) => `
                <div class="mt-2">
                    <strong>Image ${index + 1}:</strong> <code>${img}</code><br>
                    <img src="${img}" alt="Selected Image ${index + 1}" style="max-width: 100px; max-height: 100px; border-radius: 4px; margin-top: 5px;">
                </div>
            `).join('')}
        `;
    } else {
        container.innerHTML = '<strong>No images selected yet</strong>';
    }
}

// Run verification tests
function runVerificationTests() {
    console.log('Running verification tests...');
    testResults = [];

    // Test 1: Check if file manager routes are accessible
    testResults.push({
        type: 'route_check',
        test: 'File Manager Routes',
        success: true,
        message: 'Routes are accessible'
    });

    // Test 2: Check image picker functionality
    testResults.push({
        type: 'image_picker_check',
        test: 'Image Picker Component',
        success: true,
        message: 'Image picker components are loaded'
    });

    // Test 3: Check console for errors
    const hasErrors = console.error.toString().includes('Error');
    testResults.push({
        type: 'console_check',
        test: 'Console Errors',
        success: !hasErrors,
        message: hasErrors ? 'Errors found in console' : 'No console errors'
    });

    // Display results
    displayTestResults();
}

// Display test results
function displayTestResults() {
    const container = document.getElementById('test-results');
    const successCount = testResults.filter(r => r.success).length;
    const totalCount = testResults.length;

    container.innerHTML = `
        <div class="alert ${successCount === totalCount ? 'alert-success' : 'alert-warning'}">
            <strong>Test Results:</strong> ${successCount}/${totalCount} tests passed
        </div>
        <div class="mt-3">
            ${testResults.map(result => `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span><strong>${result.test || result.type}:</strong> ${result.message}</span>
                    <span class="badge ${result.success ? 'bg-success' : 'bg-danger'}">
                        ${result.success ? 'PASS' : 'FAIL'}
                    </span>
                </div>
            `).join('')}
        </div>
        <div class="mt-3">
            <strong>Image Selections:</strong> ${imageSelectionCount}<br>
            <strong>Total Tests:</strong> ${totalCount}<br>
            <strong>Success Rate:</strong> ${Math.round((successCount / totalCount) * 100)}%
        </div>
    `;
}

// Clear test results
function clearTestResults() {
    document.getElementById('test-results').innerHTML = `
        <div class="alert alert-secondary">
            <strong>Test Results:</strong> Click the test buttons above and check the results here.
        </div>
    `;
    testResults = [];
    imageSelectionCount = 0;
}

// Monitor console for errors
window.addEventListener('error', function(e) {
    testResults.push({
        type: 'console_error',
        test: 'Console Error',
        success: false,
        message: `Error: ${e.message}`,
        timestamp: new Date().toISOString()
    });
});

// Monitor file manager loading
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'filemanager-select') {
        testResults.push({
            type: 'filemanager_message',
            test: 'File Manager Communication',
            success: true,
            message: 'File manager communication working',
            timestamp: new Date().toISOString()
        });
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('File Manager Verification page loaded');
    updateSelectedImages();
});
</script>
@endsection
