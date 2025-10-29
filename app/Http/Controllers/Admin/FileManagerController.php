<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    /**
     * Display the file manager interface.
     */
    public function index()
    {
        return view('admin.file-manager.index');
    }

    /**
     * Show file manager in iframe.
     */
    public function show()
    {
        return view('admin.file-manager.show');
    }

    /**
     * Upload image for image picker.
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            ]);

            $workingDir = $request->input('working_dir', '/1/products');

            // Get the file
            $file = $request->file('image');

            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();

            // Store the file in the correct directory
            $path = $file->storeAs('files' . $workingDir, $filename, 'public');

            // Generate the URL
            $url = asset('storage/' . $path);

            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $path,
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
