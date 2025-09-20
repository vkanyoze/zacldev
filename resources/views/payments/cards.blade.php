@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'payments'])
    <main class="w-screen text-gray-700">
        <div class="w-full sm:w-full lg:w-8/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12">
             <div class="block h-16 w-full bg-white md:hidden lg:hidden"></div>
            <div class="flex justify-between items-center max-w-2xl">
                <div class="text-2xl font-bold text-custom-gray"> Enter Billing Details</div>
                <div class="font-semibold text-base">Step 2 of 3</div>
            </div>
            <div class="text-lg mt-2 flex max-w-2xl text-custom-gray">View Past Transactions, Make New Payments, and Print Receipts</div>
            <div>
                <form action="{{ route('payments.checkout') }}" method="GET">
                @csrf
                @method('GET')
                <div class="flex max-w-2xl">
                    <div class="flex-1 w-64 mr-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="first_name">First Name<label>
                            <input type="text" placeholder="First Name" value="{{ old('first_name') }}" max="5"
                                name="first_name" id="first_name" required readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('first_name') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('first_name'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('first_name') }}</span>
                                @endif
                    </div>
                    <div class="flex-1 w-32 ml-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="last_name">Last Name<label>
                            <input type="text" placeholder="Last Name"  value="{{ old('last_name') }}" 
                                name="last_name" id="last_name" readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('last_name') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('last_name'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('last_name') }}</span>
                                @endif
                    </div>
                </div>
                <div>
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_id">Select Card<label>
                        <select name="card_id" id="card_id" required class="mt-2 w-full p-2 rounded border {{ $errors->has('card_id') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal" onchange="autofillCardDetails(this)">
                            <option value="">Select a card</option>
                            @foreach($cards as $card)
                                <option value="{{ $card->id }}" data-card-number="{{ $card->card_number }}" data-first-name="{{ $card->name }}" data-last-name="{{ $card->surname }}" data-address="{{ $card->address }}" data-city="{{ $card->city }}" data-state="{{ $card->state }}" data-country="{{ $card->country }}" data-postal-code="{{ $card->postal_code }}" data-email="{{ $card->email_address }}" data-phone="{{ $card->phone_number }}">
                                    **** **** **** {{ substr($card->card_number, -4) }} - {{ $card->name }} {{ $card->surname }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="card_number" id="card_number" value="{{ old('card_number') }}">
                        <input type="hidden" name="country" id="country_hidden" value="{{ old('country') }}">
                        @if ($errors->has('card_id'))
                            <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('card_id') }}</span>
                        @endif
                </div>
                <div>
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="address">Billing Address<label>
                        <input type="text" placeholder="Your Address"  value="{{ old('address') }}"
                            name="address" id="address" required readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('address') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                            @if ($errors->has('address'))
                                <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('address') }}</span>
                            @endif
                </div>
                <div>
                <div class="flex max-w-2xl">
                    <div class="flex-1 w-64 mr-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="city">City<label>
                            <input type="text" placeholder="City" value="{{ old('city') }}"
                                name="city" id="city" required readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('city') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('city'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('city') }}</span>
                                @endif
                    </div>
                    <div class="flex-1 w-32 ml-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="postal_code">Postal Code<label>
                            <input type="text" placeholder="Postal Code" value="{{ old('postal_code') }}"
                                name="postal_code" id="postal_code" readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('postal_code') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('postal_code'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('postal_code') }}</span>
                                @endif
                    </div>
                </div>
                <div class="flex max-w-2xl">
                    <div class="flex-1 w-64 mr-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="state">Province/State<label>
                            <input type="text" placeholder="Province/State" value="{{ old('state') }}" max="5"
                                name="state" id="state" readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('state') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('state'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('state') }}</span>
                                @endif
                    </div>
                    <div class="flex-1 w-32 ml-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="country">Country<label>
                            <select name="country" id="country" readonly class="custom-select bg-gray-100 mt-2 w-full p-2 pr-4 rounded border {{ $errors->has('state') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal" disabled>
                                <option>Select Country</option>
                                @foreach ($countries as $isoCode => $countryName)
                                    <option value="{{ $isoCode }}" {{ old('country') == $isoCode ? 'selected' : '' }}>
                                        {{ $countryName }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('country'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('country') }}</span>
                            @endif
                    </div>
                </div>
                <div class="flex max-w-2xl">
                    <div class="flex-1 w-64 mr-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="email_address">Email Address<label>
                            <input type="email" placeholder="Email Address" value="{{ old('email_address') }}"
                                name="email_address" id="email_address" required readonly class="mt-2 w-full p-2 rounded border  {{ $errors->has('email_address') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('email_address'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email_address') }}</span>
                                @endif
                    </div>
                    <div class="flex-1 w-32 ml-2">
                            <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="phone_number">Phone Number<label>
                            <input type="text" placeholder="Phone number" value="{{ old('phone_number') }}"
                                name="phone_number" id="phone_number" required readonly class="mt-2 w-full p-2 rounded border {{ $errors->has('phone_number') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal bg-gray-100">
                                @if ($errors->has('phone_number'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('phone_number') }}</span>
                                @endif
                    </div>
                </div>
                <div class="flex mt-5">
                    <div class="flex items-center h-5 mr-2">
                    <input aria-describedby="helper-checkbox-text" name="save" type="checkbox" value="0" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-2 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-none dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div class="ml-1 text-sm">
                    Save card for later reuse
                    </div>
                </div>
                <div class="flex mt-8">
                    <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 bg-white rounded font-bold mt-3 mr-4 text-custom-gray border border-custom-gray w-full sm:w-full lg:w-auto md:w-auto" type="button" onclick="goBack()" id="btns">Back</button>
                    <button class="px-6 py-4 bg-green-500 text-white rounded font-bold mt-3 w-full sm:w-full lg:w-auto md:w-auto">Continue</button>
                </div>
            </form>
    </div>
            </main>
</div>
<script>
    function goBack() {
      window.history.back();
    }

    function autofillCardDetails(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        if (selectedOption.value) {
            // Autofill all form fields
            document.getElementById('first_name').value = selectedOption.dataset.firstName || '';
            document.getElementById('last_name').value = selectedOption.dataset.lastName || '';
            document.getElementById('card_number').value = selectedOption.dataset.cardNumber || '';
            document.getElementById('address').value = selectedOption.dataset.address || '';
            document.getElementById('city').value = selectedOption.dataset.city || '';
            document.getElementById('state').value = selectedOption.dataset.state || '';
            document.getElementById('country_hidden').value = selectedOption.dataset.country || '';
            document.getElementById('postal_code').value = selectedOption.dataset.postalCode || '';
            document.getElementById('email_address').value = selectedOption.dataset.email || '';
            document.getElementById('phone_number').value = selectedOption.dataset.phone || '';
            
            // Update the country dropdown display
            const countrySelect = document.getElementById('country');
            const countryValue = selectedOption.dataset.country || '';
            for (let i = 0; i < countrySelect.options.length; i++) {
                if (countrySelect.options[i].value === countryValue) {
                    countrySelect.selectedIndex = i;
                    break;
                }
            }
        } else {
            // Clear all fields if no card selected
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('card_number').value = '';
            document.getElementById('address').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('country_hidden').value = '';
            document.getElementById('postal_code').value = '';
            document.getElementById('email_address').value = '';
            document.getElementById('phone_number').value = '';
            
            // Reset country dropdown
            document.getElementById('country').selectedIndex = 0;
        }
    }
</script>
@endsection