@extends('layouts.admin')

@section('title', 'File Manager')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">File Manager</h3>
                    <div>
                        <a href="/laravel-filemanager" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-2"></i>Open in New Tab
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="file-manager-container">
                        <iframe
                            src="/laravel-filemanager"
                            width="100%"
                            height="600"
                            frameborder="0"
                            style="border: none; border-radius: 0 0 8px 8px;"
                            onload="console.log('File manager loaded successfully')"
                            onerror="console.error('Error loading file manager')">
                        </iframe>
                    </div>

                    <!-- Debug Information -->
                    <div class="mt-3 p-3 bg-light">
                        <h6>Debug Information:</h6>
                        <ul class="mb-0">
                            <li><strong>File Manager URL:</strong> <a href="/laravel-filemanager" target="_blank">/laravel-filemanager</a></li>
                            <li><strong>Storage Link:</strong> <a href="/storage" target="_blank">/storage</a></li>
                            <li><strong>Upload Directory:</strong> <code>storage/app/public/files</code></li>
                            <li><strong>Photos Directory:</strong> <code>storage/app/public/photos</code></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.file-manager-container {
    position: relative;
    width: 100%;
    height: 600px;
    overflow: hidden;
}

.file-manager-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}

@media (max-width: 768px) {
    .file-manager-container {
        height: 500px;
    }
}

@media (max-width: 576px) {
    .file-manager-container {
        height: 400px;
    }
}
</style>
@endsection
