<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-custom-gray mb-2">System Settings</h1>
        <p class="text-gray-600 text-lg">Configure your payment gateway and system preferences.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
        @csrf
        
        <!-- General Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-custom-gray mb-2">General Settings</h2>
                <p class="text-gray-600">Basic system configuration and branding.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                    <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                    @error('site_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                    @error('contact_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                    <select id="currency" name="currency" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                        <option value="ZMW" {{ old('currency', $settings['currency']) == 'ZMW' ? 'selected' : '' }}>ZMW - Zambian Kwacha</option>
                        <option value="USD" {{ old('currency', $settings['currency']) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                        <option value="EUR" {{ old('currency', $settings['currency']) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (minutes)</label>
                    <input type="number" id="session_timeout" name="session_timeout" value="{{ old('session_timeout', $settings['session_timeout']) }}"
                           min="1" max="1440" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                    @error('session_timeout')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-custom-gray mb-2">Payment Settings</h2>
                <p class="text-gray-600">Configure payment processing and limits.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="min_payment_amount" class="block text-sm font-medium text-gray-700 mb-2">Minimum Payment Amount</label>
                    <input type="number" id="min_payment_amount" name="min_payment_amount" value="{{ old('min_payment_amount', $settings['min_payment_amount']) }}"
                           min="0" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                    @error('min_payment_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="max_payment_amount" class="block text-sm font-medium text-gray-700 mb-2">Maximum Payment Amount</label>
                    <input type="number" id="max_payment_amount" name="max_payment_amount" value="{{ old('max_payment_amount', $settings['max_payment_amount']) }}"
                           min="0" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                    @error('max_payment_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="payment_timeout" class="block text-sm font-medium text-gray-700 mb-2">Payment Timeout (minutes)</label>
                    <input type="number" id="payment_timeout" name="payment_timeout" value="{{ old('payment_timeout', $settings['payment_timeout']) }}"
                           min="1" max="300" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                    @error('payment_timeout')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- System Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-custom-gray mb-2">System Settings</h2>
                <p class="text-gray-600">Control system behavior and maintenance mode.</p>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Maintenance Mode</h3>
                        <p class="text-sm text-gray-600">Enable maintenance mode to temporarily disable the system.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Auto Approve Payments</h3>
                        <p class="text-sm text-gray-600">Automatically approve payments without manual review.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_approve_payments" value="1" {{ old('auto_approve_payments', $settings['auto_approve_payments']) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Require Email Verification</h3>
                        <p class="text-sm text-gray-600">Require users to verify their email address before using the system.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="require_email_verification" value="1" {{ old('require_email_verification', $settings['require_email_verification']) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Password Policy Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-custom-gray mb-2">Password Policy</h2>
                <p class="text-gray-600">Configure password requirements for user accounts.</p>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Enable Password Policy</h3>
                        <p class="text-sm text-gray-600">Enforce password requirements for all user accounts.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="password_policy_enabled" value="1" {{ old('password_policy_enabled', $passwordPolicy->enabled) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                    </label>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password_policy_min_length" class="block text-sm font-medium text-gray-700 mb-2">Minimum Length</label>
                        <input type="number" id="password_policy_min_length" name="password_policy_min_length" 
                               value="{{ old('password_policy_min_length', $passwordPolicy->min_length) }}"
                               min="4" max="50" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-green focus:border-custom-green transition duration-300">
                        @error('password_policy_min_length')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-900">Password Requirements</h4>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h5 class="text-md font-medium text-gray-900">Require Uppercase Letters</h5>
                            <p class="text-sm text-gray-600">Password must contain at least one uppercase letter (A-Z).</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="password_policy_require_uppercase" value="1" {{ old('password_policy_require_uppercase', $passwordPolicy->require_uppercase) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h5 class="text-md font-medium text-gray-900">Require Lowercase Letters</h5>
                            <p class="text-sm text-gray-600">Password must contain at least one lowercase letter (a-z).</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="password_policy_require_lowercase" value="1" {{ old('password_policy_require_lowercase', $passwordPolicy->require_lowercase) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h5 class="text-md font-medium text-gray-900">Require Numbers</h5>
                            <p class="text-sm text-gray-600">Password must contain at least one number (0-9).</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="password_policy_require_numbers" value="1" {{ old('password_policy_require_numbers', $passwordPolicy->require_numbers) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h5 class="text-md font-medium text-gray-900">Require Special Characters</h5>
                            <p class="text-sm text-gray-600">Password must contain at least one special character (!@#$%^&*).</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="password_policy_require_special_characters" value="1" {{ old('password_policy_require_special_characters', $passwordPolicy->require_special_characters) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-custom-gray mb-2">Notification Settings</h2>
                <p class="text-gray-600">Configure how users receive notifications.</p>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Email Notifications</h3>
                        <p class="text-sm text-gray-600">Send notifications via email to users.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_notifications" value="1" {{ old('email_notifications', $settings['email_notifications']) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">SMS Notifications</h3>
                        <p class="text-sm text-gray-600">Send notifications via SMS to users.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_notifications" value="1" {{ old('sms_notifications', $settings['sms_notifications']) ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-custom-green rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-custom-green"></div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-end">
            <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold text-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Cancel
            </a>
            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-custom-green to-green-600 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 font-semibold">
                <i class="fas fa-save mr-2"></i>
                Save Settings
            </button>
        </div>
    </form>
</x-admin-layout>
