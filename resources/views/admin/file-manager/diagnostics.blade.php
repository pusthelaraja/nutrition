@extends('layouts.admin')

@section('title', 'File Manager Diagnostics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">File Manager Diagnostics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Storage Directories</h5>
                            <ul class="list-group">
                                @foreach($diagnostics['storage_directories'] as $path => $exists)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <code>{{ $path }}</code>
                                        <span class="badge {{ $exists ? 'bg-success' : 'bg-danger' }}">
                                            {{ $exists ? 'Exists' : 'Missing' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h5>Permissions</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Storage Writable</span>
                                    <span class="badge {{ $diagnostics['permissions']['storage_writable'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $diagnostics['permissions']['storage_writable'] ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Public Writable</span>
                                    <span class="badge {{ $diagnostics['permissions']['public_writable'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $diagnostics['permissions']['public_writable'] ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Public Storage Link</span>
                                    <span class="badge {{ $diagnostics['public_storage_link'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $diagnostics['public_storage_link'] ? 'Exists' : 'Missing' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Authentication</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>User Authenticated</span>
                                    <span class="badge {{ $diagnostics['authentication']['user_authenticated'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $diagnostics['authentication']['user_authenticated'] ? 'Yes' : 'No' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>User ID</span>
                                    <span>{{ $diagnostics['authentication']['user_id'] ?? 'N/A' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>User Name</span>
                                    <span>{{ $diagnostics['authentication']['user_name'] }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h5>Routes</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <strong>Laravel File Manager:</strong><br>
                                    <a href="{{ $diagnostics['routes']['laravel_filemanager_route'] }}" target="_blank" class="btn btn-primary btn-sm mt-2">
                                        Open File Manager
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Upload Test</h5>
                            <form id="uploadTestForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="test_file" class="form-label">Select a test file:</label>
                                    <input type="file" class="form-control" id="test_file" name="test_file" required>
                                </div>
                                <button type="submit" class="btn btn-success">Test Upload</button>
                            </form>
                            <div id="uploadResult" class="mt-3"></div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Quick Actions</h5>
                            <div class="btn-group" role="group">
                                <a href="{{ route('file-manager') }}" class="btn btn-primary">Back to File Manager</a>
                                <a href="/laravel-filemanager" target="_blank" class="btn btn-secondary">Direct File Manager</a>
                                <button onclick="location.reload()" class="btn btn-info">Refresh Diagnostics</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('uploadTestForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const resultDiv = document.getElementById('uploadResult');

    resultDiv.innerHTML = '<div class="alert alert-info">Uploading...</div>';

    fetch('/admin/file-manager/test-upload', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <strong>Success!</strong> ${data.message}<br>
                    <strong>Path:</strong> ${data.path}<br>
                    <strong>URL:</strong> <a href="${data.url}" target="_blank">${data.url}</a>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <strong>Error:</strong> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="alert alert-danger">
                <strong>Error:</strong> ${error.message}
            </div>
        `;
    });
});
</script>
@endsection
