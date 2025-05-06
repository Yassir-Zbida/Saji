@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Create Coupon</h1>
                <p class="mt-1 text-sm text-gray-500">Add a new discount coupon to the system</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.coupons') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Coupons
                </a>
            </div>
        </div>

        <!-- Create Coupon Form -->
        <div class="bg-white rounded-xl border border-gray-200 mb-6 transition-all duration-300 ">
            <div class="p-5 border-b border-gray-100 flex items-center">
                <i class="ri-coupon-line text-lg text-primary mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">Coupon Information</h3>
            </div>

            <form id="createCouponForm" action="{{ route('admin.coupons.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Code Field -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Coupon Code</label>
                            <input type="text" id="code" name="code" value="{{ old('code') }}"
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="e.g. SUMMER25" required>
                            <p class="mt-1 text-sm text-red-600 hidden" id="code-error"></p>
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Enter a unique code for this coupon. Customers will use this code at checkout.</p>
                        </div>

                        <!-- Type Field -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Discount Type</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="type" name="type"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage discount</option>
                                    <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>Fixed amount discount</option>
                                </select>
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="type-error"></p>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Choose whether this coupon provides a percentage or fixed amount discount.</p>
                        </div>

                        <!-- Value Field -->
                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 mb-1.5">Coupon Value</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500" id="value-prefix">%</span>
                                </div>
                                <input type="number" id="value" name="value" value="{{ old('value') }}"
                                    class="block w-full pl-8 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="e.g. 25" min="0" step="any" required>
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="value-error"></p>
                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500" id="value-help">Enter the percentage discount (0-100).</p>
                        </div>

                        <!-- Minimum Spend Field -->
                        <div>
                            <label for="minimum_spend" class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Spend</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="minimum_spend" name="minimum_spend" value="{{ old('minimum_spend') }}"
                                    class="block w-full pl-8 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="e.g. 50.00" min="0" step="0.01">
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="minimum-spend-error"></p>
                            @error('minimum_spend')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimum order amount required to use this coupon. Leave blank for no minimum.</p>
                        </div>

                        <!-- Maximum Discount Field -->
                        <div id="maximum-discount-container">
                            <label for="maximum_discount" class="block text-sm font-medium text-gray-700 mb-1.5">Maximum Discount</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount') }}"
                                    class="block w-full pl-8 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="e.g. 100.00" min="0" step="0.01">
                            </div>
                            <p class="mt-1 text-sm text-red-600 hidden" id="maximum-discount-error"></p>
                            @error('maximum_discount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Maximum discount amount for percentage coupons. Leave blank for no maximum.</p>
                        </div>

                        <!-- Usage Limit Field -->
                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-1.5">Usage Limit</label>
                            <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="e.g. 100" min="1">
                            <p class="mt-1 text-sm text-red-600 hidden" id="usage-limit-error"></p>
                            @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">How many times this coupon can be used. Leave blank for unlimited usage.</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Date Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
                                <div class="relative rounded-lg border border-gray-200">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-calendar-line text-gray-400"></i>
                                    </div>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                        class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
                                <p class="mt-1 text-sm text-red-600 hidden" id="start-date-error"></p>
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
                                <div class="relative rounded-lg border border-gray-200">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-calendar-line text-gray-400"></i>
                                    </div>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                        class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
                                <p class="mt-1 text-sm text-red-600 hidden" id="end-date-error"></p>
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Individual Use -->
                        <div class="mt-6">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="individual_use" name="individual_use" type="checkbox" value="1"
                                        {{ old('individual_use') ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border border-gray-300 rounded focus:ring-primary">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="individual_use" class="font-medium text-gray-700">Individual Use Only</label>
                                    <p class="text-gray-500">If checked, this coupon cannot be used in conjunction with other coupons.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Exclude Sale Items -->
                        <div class="mt-6">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="exclude_sale_items" name="exclude_sale_items" type="checkbox" value="1"
                                        {{ old('exclude_sale_items') ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border border-gray-300 rounded focus:ring-primary">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="exclude_sale_items" class="font-medium text-gray-700">Exclude Sale Items</label>
                                    <p class="text-gray-500">If checked, this coupon will not apply to items that are on sale.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="mt-6">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_active" name="is_active" type="checkbox" value="1"
                                        {{ old('is_active', '1') ? 'checked' : '' }}
                                        class="h-4 w-4 text-primary border border-gray-300 rounded focus:ring-primary">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-medium text-gray-700">Active</label>
                                    <p class="text-gray-500">If unchecked, this coupon will not be available for use.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="Enter a description for this coupon">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-red-600 hidden" id="description-error"></p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Optional description for internal reference.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('admin.coupons') }}"
                        class="inline-flex items-center px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="ri-close-line mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" id="submitButton"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="ri-check-line mr-2"></i>
                        Create Coupon
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
                                Coupon Created Successfully
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="success-message">
                                    The coupon has been added to the system.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 mb-4 flex flex-row items-center justify-center space-x-3 sm:px-6">
                    <a href="{{ route('admin.coupons') }}" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:text-sm">
                        Go to Coupons
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
        const form = document.getElementById('createCouponForm');
        const codeInput = document.getElementById('code');
        const typeSelect = document.getElementById('type');
        const valueInput = document.getElementById('value');
        const valuePrefix = document.getElementById('value-prefix');
        const valueHelp = document.getElementById('value-help');
        const maximumDiscountContainer = document.getElementById('maximum-discount-container');
        const submitButton = document.getElementById('submitButton');
        const successModal = document.getElementById('successModal');
        const createAnotherBtn = document.getElementById('createAnother');

        // Update value field based on type
        function updateValueField() {
            const type = typeSelect.value;
            
            if (type === 'percentage') {
                valuePrefix.textContent = '%';
                valueInput.placeholder = 'e.g. 25';
                valueInput.step = '1';
                valueInput.max = '100';
                valueHelp.textContent = 'Enter the percentage discount (0-100).';
                maximumDiscountContainer.style.display = 'block';
            } else {
                valuePrefix.textContent = '$';
                valueInput.placeholder = 'e.g. 10.00';
                valueInput.step = '0.01';
                valueInput.removeAttribute('max');
                valueHelp.textContent = 'Enter the fixed discount amount.';
                maximumDiscountContainer.style.display = 'none';
            }
        }

        // Initialize value field
        updateValueField();

        // Update value field when type changes
        typeSelect.addEventListener('change', updateValueField);

        // Form validation
        function validateForm() {
            let isValid = true;

            // Reset all error messages
            const errorElements = document.querySelectorAll('[id$="-error"]');
            errorElements.forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Validate code (required, alphanumeric)
            if (!codeInput.value.trim()) {
                document.getElementById('code-error').textContent = 'Coupon code is required';
                document.getElementById('code-error').classList.remove('hidden');
                isValid = false;
            } else if (!/^[A-Za-z0-9_-]+$/.test(codeInput.value.trim())) {
                document.getElementById('code-error').textContent = 'Coupon code can only contain letters, numbers, underscores, and hyphens';
                document.getElementById('code-error').classList.remove('hidden');
                isValid = false;
            }

            // Validate value (required, numeric, positive)
            if (!valueInput.value.trim() || isNaN(Number(valueInput.value)) || Number(valueInput.value) <= 0) {
                document.getElementById('value-error').textContent = 'Please enter a valid coupon value';
                document.getElementById('value-error').classList.remove('hidden');
                isValid = false;
            } else if (typeSelect.value === 'percentage' && Number(valueInput.value) > 100) {
                document.getElementById('value-error').textContent = 'Percentage discount cannot exceed 100%';
                document.getElementById('value-error').classList.remove('hidden');
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
                            `Coupon "${data.code}" has been added to the system.`;
                        successModal.classList.remove('hidden');
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.getElementById(
                                    `${field.replace('_', '-')}-error`);
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
                    '<i class="ri-check-line mr-2"></i> Create Coupon';
                });
        });

        // Handle "Create Another" button in success modal
        createAnotherBtn.addEventListener('click', function() {
            // Hide the success modal
            successModal.classList.add('hidden');

            // Reset the form
            form.reset();

            // Reset type-dependent fields
            updateValueField();

            // Set focus on the code field
            codeInput.focus();
        });

        // Check code availability
        let codeCheckTimeout;
        codeInput.addEventListener('input', function() {
            clearTimeout(codeCheckTimeout);

            if (this.value.trim()) {
                codeCheckTimeout = setTimeout(() => {
                    // This would be an actual API call in production
                    // For demo purposes, we'll simulate a check
                    const takenCodes = ['SUMMER25', 'WELCOME10'];

                    if (takenCodes.includes(this.value.trim().toUpperCase())) {
                        document.getElementById('code-error').textContent =
                            'This coupon code is already in use';
                        document.getElementById('code-error').classList.remove('hidden');
                    } else {
                        document.getElementById('code-error').classList.add('hidden');
                    }
                }, 500);
            }
        });
    });
</script>