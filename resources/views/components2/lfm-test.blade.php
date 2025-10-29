<!-- Simple Laravel File Manager Test Component -->
<div class="mb-3">
    <label class="form-label">Test File Manager</label>
    <div class="input-group">
        <input type="text" id="test_image" name="test_image" class="form-control" readonly>
        <button type="button"
                class="btn btn-primary"
                data-input="test_image"
                data-preview="test_preview">
            Choose Image
        </button>
    </div>
    <div id="test_preview" class="mt-2"></div>
</div>

<script>
$(document).ready(function() {
    console.log('LFM Test component loaded');
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('filemanager function available:', typeof $.fn.filemanager !== 'undefined');

    // Initialize the file manager button
    $('[data-input="test_image"]').filemanager('image', {
        prefix: '/laravel-filemanager'
    });

    // Global callback function for file manager
    window.fmSetLink = function(url, input, preview) {
        console.log('fmSetLink called with:', url, input, preview);
        document.getElementById(input).value = url;
        if (document.getElementById(preview)) {
            document.getElementById(preview).innerHTML = '<img src="/storage/' + url + '" class="img-thumbnail" style="max-width: 150px;">';
        }
    };
});
</script>
