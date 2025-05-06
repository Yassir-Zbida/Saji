@extends('layouts.admin')

@section('title', 'Edit Coupon')

@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Coupon</h1>
                <p class="mt-1 text-sm text-gray-500">Update coupon information</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.coupons') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Coupons
                </a>
            </div>
        </div>

        <!-- Edit Coupon Form -->
        <div class="bg-white rounded-xl border border-gray-200 mb-6 transition-all duration-300 ">
            <div class="p-5 border-b border-gray-100 flex items-center">
                <i class="ri-coupon-line text-lg text-primary mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">Coupon Information</h3>
            </div>

            <form id="editCouponForm" action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Code Field -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Coupon Code</label>
                            <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}"
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="e.g. SUMMER25" required>
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
                                    <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage discount</option>
                                    <option value="fixed_amount" {{ old('type', $coupon->type) == 'fixed_amount' ? 'selected' : '' }}>Fixed amount discount</option>
                                </select>
                            </div>
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
                                    <span class="text-gray-500" id="value-prefix">{{ $coupon->type === 'percentage' ? '%' : '$' }}</span>
                                </div>
                                <input type="number" id="value" name="value" value="{{ old('value', $coupon->value) }}"
                                    class="block w-full pl-8 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="{{ $coupon->type === 'percentage' ? 'e.g. 25' : 'e.g. 10.00' }}" 
                                    min="0" 
                                    {{ $coupon->type === 'percentage' ? 'max="100" step="1"' : 'step="0.01"' }} 
                                    required>
                            </div>
                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500" id="value-help">
                                {{ $coupon->type === 'percentage' ? 'Enter the percentage discount (0-100).' : 'Enter the fixed discount amount.' }}
                            </p>
                        </div>

                        <!-- Minimum Spend Field -->
                        <div>
                            <label for="minimum_spend" class="block text-sm font-medium text-gray-700 mb-1.5">Minimum Spend</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="minimum_spend" name="minimum_spend" value="{{ old('minimum_spend', $coupon->minimum_spend) }}"
                                    class="block w-full pl-8 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="e.g. 50.00" min="0" step="0.01">
                            </div>
                            @error('minimum_spend')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Minimum order amount required to use this coupon. Leave blank for no minimum.</p>
                        </div>

                        <!-- Maximum Discount Field -->
                        <div id="maximum-discount-container" {{ $coupon->type === 'fixed_amount' ? 'style="display: none;"' : '' }}>
                            <label for="maximum_discount" class="block text-sm font-medium text-gray-700 mb-1.5">Maximum Discount</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount', $coupon->maximum_discount) }}"
                                    class="block w-full pl-8 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="e.g. 100.00" min="0" step="0.01">
                            </div>
                            @error('maximum_discount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Maximum discount amount for percentage coupons. Leave blank for no maximum.</p>
                        </div>

                        <!-- Usage Information -->
                        <div>
                            <label for="usage_count" class="block text-sm font-medium text-gray-700 mb-1.5">Usage Information</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="usage_count" class="block text-xs text-gray-500 mb-1">Usage Count</label>
                                    <input type="number" id="usage_count" name="usage_count" value="{{ old('usage_count', $coupon->usage_count) }}"
                                        class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                        readonly>
                                </div>
                                <div>
                                    <label for="usage_limit" class="block text-xs text-gray-500 mb-1">Usage Limit</label>
                                    <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                        class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                        placeholder="Unlimited" min="1">
                                </div>
                            </div>
                            @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Usage count shows how many times this coupon has been used. Usage limit defines the maximum number of times it can be used.</p>
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
                                    <input type="date" id="start_date" name="start_date" 
                                        value="{{ old('start_date', $coupon->start_date ? date('Y-m-d', strtotime($coupon->start_date)) : '') }}"
                                        class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
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
                                    <input type="date" id="end_date" name="end_date" 
                                        value="{{ old('end_date', $coupon->end_date ? date('Y-m-d', strtotime($coupon->end_date)) : '') }}"
                                        class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                </div>
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
                                        {{ old('individual_use', $coupon->individual_use) ? 'checked' : '' }}
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
                                        {{ old('exclude_sale_items', $coupon->exclude_sale_items) ? 'checked' : '' }}
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
                                        {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
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
                                placeholder="Enter a description for this coupon">{{ old('description', $coupon->description) }}</textarea>
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
                        <i class="ri-save-line mr-2"></i>
                        Update Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form elements
        const form = document.getElementById('editCouponForm');
        const typeSelect = document.getElementById('type');
        const valueInput = document.getElementById('value');
        const valuePrefix = document.getElementById('value-prefix');
        const valueHelp = document.getElementById('value-help');
        const maximumDiscountContainer = document.getElementById('maximum-discount-container');
        const submitButton = document.getElementById('submitButton');

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
            const codeInput = document.getElementById('code');
            if (!codeInput.value.trim()) {
                const codeError = document.getElementById('code-error');
                if (codeError) {
                    codeError.textContent = 'Coupon code is required';
                    codeError.classList.remove('hidden');
                }
                isValid = false;
            } else if (!/^[A-Za-z0-9_-]+$/.test(codeInput.value.trim())) {
                const codeError = document.getElementById('code-error');
                if (codeError) {
                    codeError.textContent = 'Coupon code can only contain letters, numbers, underscores, and hyphens';
                    codeError.classList.remove('hidden');
                }
                isValid = false;
            }

            // Validate value (required, numeric, positive)
            if (!valueInput.value.trim() || isNaN(Number(valueInput.value)) || Number(valueInput.value) <= 0) {
                const valueError = document.getElementById('value-error');
                if (valueError) {
                    valueError.textContent = 'Please enter a valid coupon value';
                    valueError.classList.remove('hidden');
                }
                isValid = false;
            } else if (typeSelect.value === 'percentage' && Number(valueInput.value) > 100) {
                const valueError = document.getElementById('value-error');
                if (valueError) {
                    valueError.textContent = 'Percentage discount cannot exceed 100%';
                    valueError.classList.remove('hidden');
                }
                isValid = false;
            }

            return isValid;
        }

        // Form submission
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                
                // Scroll to the first error
                const firstError = document.querySelector('.text-red-600:not(.hidden)');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Updating...';
            }
        });
    });
</script>