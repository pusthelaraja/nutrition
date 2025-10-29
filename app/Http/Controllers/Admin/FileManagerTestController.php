<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FileManagerTestController extends Controller
{
    public function index()
    {
        $diagnostics = [];

        // Check if storage directories exist
        $diagnostics['storage_directories'] = [
            'storage/app/public' => is_dir(storage_path('app/public')),
            'storage/app/public/files' => is_dir(storage_path('app/public/files')),
            'storage/app/public/photos' => is_dir(storage_path('app/public/photos')),
        ];

        // Check if public storage link exists
        $diagnostics['public_storage_link'] = is_link(public_path('storage'));

        // Check file permissions
        $diagnostics['permissions'] = [
            'storage_writable' => is_writable(storage_path('app')),
            'public_writable' => is_writable(public_path()),
        ];

        // Check Laravel File Manager routes
        $diagnostics['routes'] = [
            'laravel_filemanager_route' => route('unisharp.lfm.show'),
            'filemanager_route' => route('unisharp.lfm.show'),
        ];

        // Check if user is authenticated
        $diagnostics['authentication'] = [
            'user_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name ?? 'N/A',
        ];

        // Check disk configuration
        $diagnostics['disk_config'] = [
            'default_disk' => config('filesystems.default'),
            'public_disk' => config('filesystems.disks.public'),
        ];

        return view('admin.file-manager.diagnostics', compact('diagnostics'));
    }

    public function testUpload(Request $request)
    {
        if ($request->hasFile('test_file')) {
            $file = $request->file('test_file');
            $filename = 'test_' . time() . '.' . $file->getClientOriginalExtension();

            try {
                $path = $file->storeAs('public/files', $filename);
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'path' => $path,
                    'url' => Storage::url($path)
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Upload failed: ' . $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'No file provided'
        ]);
    }
}
