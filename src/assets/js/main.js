// Main JavaScript file

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Image preview on file input change
    let imageInputs = document.querySelectorAll('input[type="file"]');
    imageInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            let file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                let preview = input.parentElement.querySelector('img');

                if (!preview) {
                    preview = document.createElement('img');
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxWidth = '200px';
                    input.parentElement.appendChild(preview);
                }

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            }
        });
    });

    // Quantity input validation in product detail
    let quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let max = parseInt(this.getAttribute('max'));
            let value = parseInt(this.value);

            if (value < 1) {
                this.value = 1;
            } else if (value > max) {
                this.value = max;
                alert('Số lượng tối đa có thể đặt là ' + max);
            }
        });
    }
});