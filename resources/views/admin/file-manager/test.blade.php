@extends('layouts.admin')

@section('title', 'File Manager Test')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">File Manager Test</h3>
                </div>
                <div class="card-body">
                    <h5>File Manager Status Check</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Direct Links:</h6>
                            <ul>
                                <li><a href="/laravel-filemanager" target="_blank" class="btn btn-primary btn-sm">Open File Manager</a></li>
                                <li><a href="/filemanager" target="_blank" class="btn btn-secondary btn-sm">Alternative Route</a></li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h6>Storage Check:</h6>
                            <ul>
                                <li><a href="/storage" target="_blank" class="btn btn-info btn-sm">Storage Link</a></li>
                                <li><a href="/storage/files" target="_blank" class="btn btn-warning btn-sm">Files Directory</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Upload Test:</h6>
                        <form action="/laravel-filemanager/upload" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Select File to Upload:</label>
                                <input type="file" class="form-control" id="file" name="upload" required>
                            </div>
                            <button type="submit" class="btn btn-success">Test Upload</button>
                        </form>
                    </div>

                    <div class="mt-4">
                        <h6>Debug Information:</h6>
                        <ul>
                            <li><strong>Current User:</strong> {{ auth()->user()->name ?? 'Not logged in' }}</li>
                            <li><strong>User ID:</strong> {{ auth()->id() ?? 'N/A' }}</li>
                            <li><strong>Storage Path:</strong> {{ storage_path('app/public') }}</li>
                            <li><strong>Public Path:</strong> {{ public_path('storage') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
