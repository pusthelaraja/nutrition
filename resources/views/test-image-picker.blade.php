<!DOCTYPE html>
<html>
<head>
    <title>Test Image Picker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Image Picker</h1>

        <x-image-picker
            name="test_image"
            label="Test Image"
            placeholder="Click to select image"
            class="mb-3" />

        <div class="mt-3">
            <h3>Debug Info:</h3>
            <p>Current working directory: <code>/1/products</code></p>
            <p>File manager URL: <code>/laravel-filemanager?type=Images&working_dir=/1/products&CKEditor=image-picker&CKEditorFuncNum=1&langCode=en</code></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
