console.log('Order show JS loaded!');

document.addEventListener('DOMContentLoaded', function() {
    // Get the CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Status update form handling
    const statusForm = document.getElementById('update-status-form');
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get the order ID from the URL
            const urlParts = window.location.pathname.split('/');
            const orderId = urlParts[urlParts.indexOf('orders') + 1];
            
            // Create form data
            const formData = new FormData(statusForm);
            const statusData = {
                status: formData.get('status'),
                status_comment: formData.get('status_comment')
            };
            
            // Send AJAX request
            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(statusData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                alert('Order status updated successfully!');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update order status. Please try again.');
            });
        });
    }
    
    // Payment status update form handling
    const paymentForm = document.getElementById('update-payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get the order ID from the URL
            const urlParts = window.location.pathname.split('/');
            const orderId = urlParts[urlParts.indexOf('orders') + 1];
            
            // Create form data
            const formData = new FormData(paymentForm);
            const paymentData = {
                payment_status: formData.get('payment_status'),
                transaction_id: formData.get('transaction_id')
            };
            
            // Send AJAX request
            fetch(`/admin/orders/${orderId}/payment`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(paymentData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                alert('Payment status updated successfully!');
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update payment status. Please try again.');
            });
        });
    }
});

function confirmDelete() {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        // Get the order ID from the URL
        const urlParts = window.location.pathname.split('/');
        const orderId = urlParts[urlParts.indexOf('orders') + 1];
        console.log('Delete - Order ID:', orderId);
        
        // Create a form element
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/orders/${orderId}`;
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        // Add method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Add form to document and submit
        document.body.appendChild(form);
        form.submit();
    }
}