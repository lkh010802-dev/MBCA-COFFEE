const phoneInput = document.getElementById('phone');

if (phoneInput) {
    phoneInput.addEventListener('input', function () {
        let value = this.value.replace(/\D/g, '').substring(0, 11);

        if (value.length < 4) {
            this.value = value;
        } else if (value.length < 8) {
            this.value = value.replace(/(\d{3})(\d+)/, '$1-$2');
        } else {
            this.value = value.replace(/(\d{3})(\d{4})(\d+)/, '$1-$2-$3');
        }
    });
}
