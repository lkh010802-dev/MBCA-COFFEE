const imageInput = document.querySelector('input[name="image"]');
const imagePreview = document.getElementById('preview');

if (imageInput && imagePreview) {
    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.addEventListener('load', function (event) {
            imagePreview.src = event.target.result;
            imagePreview.style.display = 'block';
        });
        reader.readAsDataURL(file);
    });
}
