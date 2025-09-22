@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'payments'])
    <main class="w-screen text-gray-700">
        <div class="w-full sm:w-full lg:w-8/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12">
             <div class="block h-16 w-full bg-white md:hidden lg:hidden"></div>
            <div class="flex justify-between items-center max-w-2xl">
                <div class="text-2xl font-bold text-custom-gray">Enter Card Details</div>
                <div class="font-semibold text-base">Step 2 of 2</div>
            </div>
            <div class="text-lg mt-2 flex max-w-2xl text-custom-gray">Complete your payment by entering your card information</div>
            
            <form action="{{ route('payments.process') }}" method="POST" id="cardForm">
                @csrf
                
                <!-- Card Information Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-custom-gray mb-4">Card Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="first_name">First Name</label>
                            <input type="text" 
                                   id="first_name" 
                                   name="first_name" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('first_name') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('first_name'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('first_name') }}</span>
                            @endif
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="last_name">Last Name</label>
                            <input type="text" 
                                   id="last_name" 
                                   name="last_name" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('last_name') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('last_name'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('last_name') }}</span>
                            @endif
                        </div>

                        <!-- Card Number -->
                        <div class="md:col-span-2">
                            <label class="block text-custom-gray font-bold mb-2" for="card_number">Card Number</label>
                            <div class="relative">
                                <input type="password" 
                                       id="card_number" 
                                       name="card_number" 
                                       maxlength="19"
                                       class="w-full p-3 pr-12 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('card_number') ? 'border-red-500' : '' }}"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                </div>
                            </div>
                            @if ($errors->has('card_number'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('card_number') }}</span>
                            @endif
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="expiry_month">Expiry Month</label>
                            <select id="expiry_month" 
                                    name="expiry_month" 
                                    class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('expiry_month') ? 'border-red-500' : '' }}"
                                    required>
                                <option value="">Month</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            @if ($errors->has('expiry_month'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('expiry_month') }}</span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="expiry_year">Expiry Year</label>
                            <select id="expiry_year" 
                                    name="expiry_year" 
                                    class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('expiry_year') ? 'border-red-500' : '' }}"
                                    required>
                                <option value="">Year</option>
                                @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @if ($errors->has('expiry_year'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('expiry_year') }}</span>
                            @endif
                        </div>

                        <!-- CVV -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="cvv">CVV</label>
                            <input type="password" 
                                   id="cvv" 
                                   name="cvv" 
                                   maxlength="3"
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('cvv') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('cvv'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('cvv') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Billing Information Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-custom-gray mb-4">Billing Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-custom-gray font-bold mb-2" for="address">Billing Address</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('address') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('address'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('address') }}</span>
                            @endif
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="city">City</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('city') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('city'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('city') }}</span>
                            @endif
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="state">State/Province</label>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('state') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('state'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('state') }}</span>
                            @endif
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="country">Country</label>
                            <select id="country" 
                                    name="country" 
                                    class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('country') ? 'border-red-500' : '' }}"
                                    required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $isoCode => $countryName)
                                    <option value="{{ $isoCode }}">{{ $countryName }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('country'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('country') }}</span>
                            @endif
                        </div>

                        <!-- Postal Code -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="postal_code">Postal Code</label>
                            <input type="text" 
                                   id="postal_code" 
                                   name="postal_code" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('postal_code') ? 'border-red-500' : '' }}">
                            @if ($errors->has('postal_code'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('postal_code') }}</span>
                            @endif
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="email_address">Email Address</label>
                            <input type="email" 
                                   id="email_address" 
                                   name="email_address" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('email_address') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('email_address'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('email_address') }}</span>
                            @endif
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-custom-gray font-bold mb-2" for="phone_number">Phone Number</label>
                            <input type="text" 
                                   id="phone_number" 
                                   name="phone_number" 
                                   class="w-full p-3 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-indigo-600 {{ $errors->has('phone_number') ? 'border-red-500' : '' }}"
                                   required>
                            @if ($errors->has('phone_number'))
                                <span class="text-sm text-red-600 mt-1">{{ $errors->first('phone_number') }}</span>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Payment Summary -->
                <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-custom-gray mb-4">Payment Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Invoice Reference:</span>
                            <span class="font-semibold">{{ $stepOne['invoice_reference'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Service:</span>
                            <span class="font-semibold">{{ $stepOne['service_type'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-semibold text-lg">${{ number_format($stepOne['amount_spend'], 2) }} {{ $stepOne['currency'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex mt-8 space-x-4">
                    <button type="button" 
                            onclick="window.history.back()" 
                            class="flex-1 px-6 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                        Back
                    </button>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-green-500 text-white rounded-lg font-semibold hover:bg-green-600 transition-colors">
                        Complete Payment
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Format card number to only allow numbers (no spaces for password field)
    document.getElementById('card_number').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    // Format CVV to only allow numbers
    document.getElementById('cvv').addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    // Format phone number
    document.getElementById('phone_number').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                e.target.value = value;
            } else if (value.length <= 6) {
                e.target.value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
            } else {
                e.target.value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
            }
        }
    });

    // Function to detect card type based on first digit
    function detectCardType(cardNumber) {
        const firstDigit = cardNumber.charAt(0);
        if (firstDigit === '4') {
            return 'visa';
        } else if (firstDigit === '5') {
            return 'mastercard';
        } else if (firstDigit === '3') {
            return 'amex';
        } else if (firstDigit === '6') {
            return 'discover';
        }
        return 'unknown';
    }

    // Real-time card type validation
    document.getElementById('card_number').addEventListener('input', function(e) {
        const cardNumber = e.target.value;
        if (cardNumber.length >= 1) {
            const cardType = detectCardType(cardNumber);
            const icon = document.querySelector('.fab.fa-cc-visa');
            
            if (cardType === 'visa') {
                icon.className = 'fab fa-cc-visa text-2xl text-blue-600';
            } else if (cardType === 'mastercard') {
                icon.className = 'fab fa-cc-mastercard text-2xl text-red-600';
            } else if (cardType === 'amex') {
                icon.className = 'fab fa-cc-amex text-2xl text-blue-500';
            } else if (cardType === 'discover') {
                icon.className = 'fab fa-cc-discover text-2xl text-orange-600';
            } else {
                icon.className = 'fab fa-credit-card text-2xl text-gray-600';
            }
        }
    });

    // Form validation
    document.getElementById('cardForm').addEventListener('submit', function(e) {
        const cardNumber = document.getElementById('card_number').value;
        const cvv = document.getElementById('cvv').value;
        
        if (cardNumber.length !== 16) {
            e.preventDefault();
            alert('Please enter a valid 16-digit card number');
            return;
        }
        
        if (cvv.length !== 3) {
            e.preventDefault();
            alert('Please enter a valid 3-digit CVV');
            return;
        }

        // Check if card is VISA
        const cardType = detectCardType(cardNumber);
        if (cardType !== 'visa') {
            e.preventDefault();
            
            let cardProviderName = 'Unknown';
            if (cardType === 'mastercard') cardProviderName = 'Mastercard';
            else if (cardType === 'amex') cardProviderName = 'American Express';
            else if (cardType === 'discover') cardProviderName = 'Discover';
            
            const message = `Sorry, we only accept VISA cards at this time.\n\nYou entered a ${cardProviderName} card.\n\nPlease use a VISA card to complete your payment.`;
            
            if (confirm(message + '\n\nDo you want to continue with a different card?')) {
                // Clear the card number field
                document.getElementById('card_number').value = '';
                document.getElementById('card_number').focus();
                // Reset icon to Visa
                const icon = document.querySelector('.fab');
                icon.className = 'fab fa-cc-visa text-2xl text-blue-600';
            }
            return;
        }
    });
</script>
@endsection
