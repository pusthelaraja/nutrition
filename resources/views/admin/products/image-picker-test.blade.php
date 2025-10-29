@extends('layouts.admin')

@section('title', 'Image Picker Test')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Image Picker Test</h3>
                </div>
                <div class="card-body">
                    <h5>Test Image Picker Connection</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Direct File Manager Links:</h6>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="/laravel-filemanager" target="_blank" class="btn btn-primary btn-sm">
                                        Open Laravel File Manager (Default)
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="/laravel-filemanager?working_dir=/shares" target="_blank" class="btn btn-success btn-sm">
                                        Open Shared Folder (/shares)
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="/laravel-filemanager?working_dir=/1/products" target="_blank" class="btn btn-warning btn-sm">
                                        Open User Products (/1/products)
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="/admin/file-manager" target="_blank" class="btn btn-info btn-sm">
                                        Open Admin File Manager
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h6>Test Image Picker:</h6>
                            <form>
                                <div class="mb-3">
                                    <x-image-picker
                                        name="test_image"
                                        label="Test Image"
                                        placeholder="Click to test image picker"
                                        value=""
                                        class="mb-3" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Selected Image Path:</label>
                                    <input type="text" class="form-control" id="selected-image-path" readonly>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Debug Information:</h6>
                            <div class="alert alert-info">
                                <strong>Current User:</strong> {{ auth()->user()->name ?? 'Not logged in' }}<br>
                                <strong>User ID:</strong> {{ auth()->id() ?? 'N/A' }}<br>
                                <strong>File Manager URL:</strong> <code>/laravel-filemanager</code><br>
                                <strong>Admin File Manager URL:</strong> <code>/admin/file-manager</code><br>
                                <strong>Shared Folder:</strong> <code>/shares</code><br>
                                <strong>User Products Folder:</strong> <code>/{{ auth()->id() ?? '1' }}/products</code>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6>Instructions:</h6>
                            <ol>
                                <li><strong>Test Direct Links:</strong> Click the file manager links above to see which one works</li>
                                <li><strong>Test Image Picker:</strong> Click "Select Image" in the image picker above</li>
                                <li><strong>Check Console:</strong> Open browser console (F12) to see any errors</li>
                                <li><strong>Upload Test:</strong> Try uploading an image in the file manager</li>
                                <li><strong>Select Test:</strong> Try selecting an existing image</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Debug script to monitor image picker
document.addEventListener('DOMContentLoaded', function() {
    console.log('Image picker test page loaded');

    // Monitor for image selection
    window.setImageUrl = function(url) {
        console.log('Image selected:', url);
        document.getElementById('selected-image-path').value = url;
    };

    // Monitor for any errors
    window.addEventListener('error', function(e) {
        console.error('Error detected:', e);
    });
});
</script>
@endsection
