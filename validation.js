document.addEventListener('DOMContentLoaded', () => {
    // Function to validate quantity input
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function () {
            const maxQuantity = parseInt(this.max);
            const enteredQuantity = parseInt(this.value);

            if (enteredQuantity > maxQuantity) {
                alert(`Quantity entered exceeds available stock (${maxQuantity}). Please enter a valid quantity.`);
                this.value = maxQuantity;
            }
        });
    });
});

// Validates restock quantities
function validateRestockForm() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    let valid = true;
    let errorMessage = '';

    quantityInputs.forEach(input => {
        const quantity = parseInt(input.value);

        // Checks if the quantity is within a valid range
        if (isNaN(quantity) || quantity < 0 || quantity > 1000) {
            errorMessage = 'Please enter a valid quantity (0 - 1000) for all items.';
            input.focus(); // Focus on the invalid field
            valid = false;
        }
    });

    // If there's an error, show a custom error message and prevent form submission
    if (!valid) {
        document.getElementById('error-message').innerText = errorMessage;
        return false;
    }

    return true;
}





