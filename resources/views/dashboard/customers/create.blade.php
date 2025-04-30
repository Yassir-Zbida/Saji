@extends('layouts.admin')

@section('title', 'Create Customer')

@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Create Customer</h1>
                <p class="mt-1 text-sm text-gray-500">Add a new customer account to the system</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.customers') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Customers
                </a>
            </div>
        </div>

        <!-- Create Customer Form -->
        <div class="bg-white rounded-xl border border-gray-200 mb-6 transition-all duration-300 ">
            <div class="p-5 border-b border-gray-100 flex items-center">
                <i class="ri-user-add-line text-lg text-primary mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
            </div>

            <form id="createCustomerForm" action="{{ route('admin.customers.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="John Doe" required>
                            <p class="mt-1 text-sm text-red-600 hidden" id="name-error"></p>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email
                                Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="john.doe@example.com" required>
                            <p class="mt-1 text-sm text-red-600 hidden" id="email-error"></p>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="••••••••" required>
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                            </div>
                            <div class="mt-2">
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="password-strength" class="h-1.5 rounded-full bg-gray-300" style="width: 0%">
                                    </div>
                                </div>
                                <p id="password-strength-text" class="mt-1 text-xs text-gray-500">Password strength: Too
                                    weak</p>
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="password-error"></p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="••••••••" required>
                                <button type="button" id="toggleConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="password-confirmation-error"></p>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="role" name="role"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager
                                    </option>
                                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }} selected>
                                        Customer</option>
                                    <option value="support_agent" {{ old('role') == 'support_agent' ? 'selected' : '' }}>
                                        Support Agent</option>
                                </select>
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="role-error"></p>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone
                                Number</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="+1 (555) 123-4567">
                            <p class="mt-1 text-sm text-red-600 hidden" id="phone-error"></p>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Address Field -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="123 Main St">
                            <p class="mt-1 text-sm text-red-600 hidden" id="address-error"></p>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City and State Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="New York">
                                <p class="mt-1 text-sm text-red-600 hidden" id="city-error"></p>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="state"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">State/Province</label>
                                <input type="text" id="state" name="state" value="{{ old('state') }}"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="NY">
                                <p class="mt-1 text-sm text-red-600 hidden" id="state-error"></p>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Zip Code and Country Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1.5">ZIP/Postal
                                    Code</label>
                                <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code') }}"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="10001">
                                <p class="mt-1 text-sm text-red-600 hidden" id="zip-code-error"></p>
                                @error('zip_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="country"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">Country</label>
                                <select id="country" name="country"
                                    class="block w-full pl-4 pr-10 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">Select a country</option>
                                    <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States
                                    </option>
                                    <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>United Kingdom
                                    </option>
                                    <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia
                                    </option>
                                    <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                                    <option value="JP" {{ old('country') == 'JP' ? 'selected' : '' }}>Japan</option>
                                    <!-- Add more countries as needed -->
                                </select>
                                <p class="mt-1 text-sm text-red-600 hidden" id="country-error"></p>
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Verification -->
                        <div class="mt-6">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="email_verified" name="email_verified" type="checkbox" value="1"
                                        {{ old('email_verified') ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border border-gray-300 rounded focus:ring-primary">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="email_verified" class="font-medium text-gray-700">Mark Email as
                                        Verified</label>
                                    <p class="text-gray-500">Skip the email verification process for this user</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Additional
                                Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="Any additional information about this customer">{{ old('notes') }}</textarea>
                            <p class="mt-1 text-sm text-red-600 hidden" id="notes-error"></p>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('admin.customers') }}"
                        class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="ri-close-line mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" id="submitButton"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="ri-user-add-line mr-2"></i>
                        Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Modal -->
<div id="successModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex flex-col items-center justify-center">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                        <i class="ri-check-line text-green-600"></i>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Customer Created Successfully
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="success-message">
                                The customer has been added to the system.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 mb-4 flex flex-row items-center justify-center space-x-3 sm:px-6">
                <a href="{{ route('admin.customers') }}" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm">
                    Go to Customers
                </a>
                <button type="button" id="createAnother" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm">
                    Create Another
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form elements
        const form = document.getElementById('createCustomerForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const submitButton = document.getElementById('submitButton');
        const successModal = document.getElementById('successModal');
        const createAnotherBtn = document.getElementById('createAnother');

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="ri-eye-line text-lg"></i>' :
                '<i class="ri-eye-off-line text-lg"></i>';
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="ri-eye-line text-lg"></i>' :
                '<i class="ri-eye-off-line text-lg"></i>';
        });

        // Password strength meter
        const passwordStrength = document.getElementById('password-strength');
        const passwordStrengthText = document.getElementById('password-strength-text');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = '';

            // Calculate password strength
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/)) strength += 12.5;
            if (password.match(/[^a-zA-Z0-9]+/)) strength += 12.5;

            // Update strength meter
            passwordStrength.style.width = strength + '%';

            // Set color based on strength
            if (strength < 25) {
                passwordStrength.className = 'h-1.5 rounded-full bg-red-500';
                feedback = 'Too weak';
            } else if (strength < 50) {
                passwordStrength.className = 'h-1.5 rounded-full bg-orange-500';
                feedback = 'Weak';
            } else if (strength < 75) {
                passwordStrength.className = 'h-1.5 rounded-full bg-yellow-500';
                feedback = 'Good';
            } else {
                passwordStrength.className = 'h-1.5 rounded-full bg-green-500';
                feedback = 'Strong';
            }

            passwordStrengthText.textContent = 'Password strength: ' + feedback;
        });

        // Phone number formatting
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = '+1 (' + value;
                } else if (value.length <= 6) {
                    value = '+1 (' + value.substring(0, 3) + ') ' + value.substring(3);
                } else if (value.length <= 10) {
                    value = '+1 (' + value.substring(0, 3) + ') ' + value.substring(3, 6) + '-' + value
                        .substring(6);
                } else {
                    value = '+1 (' + value.substring(0, 3) + ') ' + value.substring(3, 6) + '-' + value
                        .substring(6, 10);
                }
            }
            e.target.value = value;
        });

        // Role-based field requirements
        const roleSelect = document.getElementById('role');

        roleSelect.addEventListener('change', function() {
            const role = this.value;

            // Make certain fields required based on role
            if (role === 'admin' || role === 'manager') {
                phoneInput.setAttribute('required', 'required');
                document.querySelector('label[for="phone"]').innerHTML =
                    'Phone Number <span class="text-red-500">*</span>';
            } else {
                phoneInput.removeAttribute('required');
                document.querySelector('label[for="phone"]').textContent = 'Phone Number';
            }
        });

        // Address autocomplete (simplified example)
        addressInput.addEventListener('input', function() {
            // This is a placeholder for address autocomplete functionality
            // In a real implementation, you would integrate with a service like Google Places API
            console.log('Address input changed:', this.value);

            // Example of how you might populate city/state/zip based on address
            if (this.value.toLowerCase().includes('new york')) {
                document.getElementById('city').value = 'New York';
                document.getElementById('state').value = 'NY';
                document.getElementById('zip_code').value = '10001';
                document.getElementById('country').value = 'US';
            }
        });

        // Form validation
        function validateForm() {
            let isValid = true;

            // Reset all error messages
            const errorElements = document.querySelectorAll('[id$="-error"]');
            errorElements.forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Validate name (required, min length 2)
            if (!nameInput.value.trim() || nameInput.value.trim().length < 2) {
                document.getElementById('name-error').textContent = 'Name must be at least 2 characters';
                document.getElementById('name-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate email (required, valid format)
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailInput.value.trim() || !emailRegex.test(emailInput.value.trim())) {
                document.getElementById('email-error').textContent = 'Please enter a valid email address';
                document.getElementById('email-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate password (required, min length 8)
            if (!passwordInput.value || passwordInput.value.length < 8) {
                document.getElementById('password-error').textContent =
                'Password must be at least 8 characters';
                document.getElementById('password-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate password confirmation (must match password)
            if (passwordInput.value !== passwordConfirmInput.value) {
                document.getElementById('password-confirmation-error').textContent = 'Passwords do not match';
                document.getElementById('password-confirmation-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate phone if required
            if (phoneInput.hasAttribute('required') && !phoneInput.value.trim()) {
                document.getElementById('phone-error').textContent = 'Phone number is required for this role';
                document.getElementById('phone-error').classList.remove('hidden');
                isValid = false;
            }

            return isValid;
        }

        // Form submission with AJAX
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) {
                // Scroll to the first error
                const firstError = document.querySelector('[id$="-error"]:not(.hidden)');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                return;
            }

            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Creating...';

            // Create FormData object
            const formData = new FormData(form);

            // Send AJAX request
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success modal
                        document.getElementById('success-message').textContent =
                            `${data.name} has been added to the system.`;
                        successModal.classList.remove('hidden');
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(
                                    `${field}-error`);
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                    errorElement.classList.remove('hidden');
                                }
                            });

                            // Scroll to the first error
                            const firstError = document.querySelector(
                            '[id$="-error"]:not(.hidden)');
                            if (firstError) {
                                firstError.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }
                        } else {
                            // Handle other errors
                            alert('An error occurred. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML =
                    '<i class="ri-user-add-line mr-2"></i> Create Customer';
                });
        });

        // Handle "Create Another" button in success modal
        createAnotherBtn.addEventListener('click', function() {
            // Hide the success modal
            successModal.classList.add('hidden');

            // Reset the form
            form.reset();

            // Reset password strength meter
            passwordStrength.style.width = '0%';
            passwordStrength.className = 'h-1.5 rounded-full bg-gray-300';
            passwordStrengthText.textContent = 'Password strength: Too weak';

            // Set focus on the name field
            nameInput.focus();
        });

        // Address lookup functionality (example implementation)
        function lookupAddress(zipCode) {
            // This is a simplified example - in production, you would use an API
            const zipCodeMap = {
                '10001': {
                    city: 'New York',
                    state: 'NY',
                    country: 'US'
                },
                '90210': {
                    city: 'Beverly Hills',
                    state: 'CA',
                    country: 'US'
                },
                '60601': {
                    city: 'Chicago',
                    state: 'IL',
                    country: 'US'
                },
                // Add more mappings as needed
            };

            const zipData = zipCodeMap[zipCode];
            if (zipData) {
                document.getElementById('city').value = zipData.city;
                document.getElementById('state').value = zipData.state;
                document.getElementById('country').value = zipData.country;
            }
        }

        // Zip code lookup
        const zipCodeInput = document.getElementById('zip_code');
        zipCodeInput.addEventListener('blur', function() {
            if (this.value.length >= 5) {
                lookupAddress(this.value);
            }
        });

        // Email availability check
        let emailCheckTimeout;
        emailInput.addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);

            // Only check if email is valid format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value.trim())) {
                emailCheckTimeout = setTimeout(() => {
                    // This would be an actual API call in production
                    // For demo purposes, we'll simulate a check
                    const takenEmails = ['admin@example.com', 'test@example.com'];

                    if (takenEmails.includes(this.value.trim().toLowerCase())) {
                        document.getElementById('email-error').textContent =
                            'This email is already in use';
                        document.getElementById('email-error').classList.remove('hidden');
                    } else {
                        document.getElementById('email-error').classList.add('hidden');
                    }
                }, 500);
            }
        });

        // Role-specific UI adjustments
        function updateUIForRole(role) {
            const adminFields = document.querySelectorAll('.admin-only');
            const customerFields = document.querySelectorAll('.customer-only');

            if (role === 'admin' || role === 'manager') {
                adminFields.forEach(field => field.classList.remove('hidden'));
                customerFields.forEach(field => field.classList.add('hidden'));
            } else {
                adminFields.forEach(field => field.classList.add('hidden'));
                customerFields.forEach(field => field.classList.remove('hidden'));
            }
        }

        // Initialize UI based on default role
        updateUIForRole(roleSelect.value);

        // Update UI when role changes
        roleSelect.addEventListener('change', function() {
            updateUIForRole(this.value);
        });
    });
</script>
