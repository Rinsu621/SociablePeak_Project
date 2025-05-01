document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('image');
    input.addEventListener('change', previewImage);
});

function previewImage(event) {
    console.log('Image selected'); // Test if this is firing
    var files = event.target.files;
    var preview = document.getElementById('image-preview');
    preview.innerHTML = ''; // Clear the existing preview

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();

        // Create a closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '200px';
                img.style.marginRight = '10px';
                preview.appendChild(img);
            };
        })(file);

        reader.readAsDataURL(file);
    }
}
