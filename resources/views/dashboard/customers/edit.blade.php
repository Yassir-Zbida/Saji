@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Customer</h1>
                <p class="mt-1 text-sm text-gray-500">Update customer account information</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.customers') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Customers
                </a>
            </div>
        </div>

        <!-- Edit Customer Form -->
        <div class="bg-white rounded-xl border border-gray-200 mb-6 transition-all duration-300 ">
            <div class="p-5 border-b border-gray-100 flex items-center">
                <i class="ri-user-settings-line text-lg text-primary mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">Customer Information</h3>
            </div>

            <form id="editCustomerForm" action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="John Doe" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email
                                Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="john.doe@example.com" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-sm text-gray-500">(leave blank to keep current password)</span></label>
                            <div class="relative">
                                <input type="password" id="password" name="password" autocomplete="new-password"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="••••••••">
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
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="••••••••">
                                <button type="button" id="toggleConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i class="ri-eye-line text-lg"></i>
                                </button>
                            </div>
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
                                    <option value="admin" {{ old('role', $customer->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ old('role', $customer->role) == 'manager' ? 'selected' : '' }}>Manager
                                    </option>
                                    <option value="customer" {{ old('role', $customer->role) == 'customer' ? 'selected' : '' }}>
                                        Customer</option>
                                    <option value="support_agent" {{ old('role', $customer->role) == 'support_agent' ? 'selected' : '' }}>
                                        Support Agent</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone
                                Number</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="+1 (555) 123-4567">
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
                            <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}"
                                class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="123 Main St">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City and State Fields -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city', $customer->city) }}"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="New York">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="state"
                                    class="block text-sm font-medium text-gray-700 mb-1.5">State/Province</label>
                                <input type="text" id="state" name="state" value="{{ old('state', $customer->state) }}"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="NY">
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
                                <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', $customer->zip_code) }}"
                                    class="block w-full px-4 py-2.5 border rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="10001">
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
                                    <option value="US" {{ old('country', $customer->country) == 'US' ? 'selected' : '' }}>United States
                                    </option>
                                    <option value="CA" {{ old('country', $customer->country) == 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="GB" {{ old('country', $customer->country) == 'GB' ? 'selected' : '' }}>United Kingdom
                                    </option>
                                    <option value="AU" {{ old('country', $customer->country) == 'AU' ? 'selected' : '' }}>Australia
                                    </option>
                                    <option value="FR" {{ old('country', $customer->country) == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="DE" {{ old('country', $customer->country) == 'DE' ? 'selected' : '' }}>Germany</option>
                                    <option value="JP" {{ old('country', $customer->country) == 'JP' ? 'selected' : '' }}>Japan</option>
                                    <!-- Add more countries as needed -->
                                </select>
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
                                        {{ $customer->email_verified_at ? 'checked' : '' }}
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
                                placeholder="Any additional information about this customer">{{ old('notes', $customer->notes) }}</textarea>
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
                        <i class="ri-save-line mr-2"></i>
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form elements
        const form = document.getElementById('editCustomerForm');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const phoneInput = document.getElementById('phone');
        const addressInput = document.getElementById('address');
        const roleSelect = document.getElementById('role');

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

        // Initialize UI based on current role
        updateUIForRole(roleSelect.value);

        // Update UI when role changes
        roleSelect.addEventListener('change', function() {
            updateUIForRole(this.value);
        });
    });
</script>