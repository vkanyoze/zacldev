@extends('app')
@section('content')
@include('header')
@include('side-bar', ['activeNavItem' => 'cards'])
<main class="py-16 flex justify-center">
    <div class="w-8/12">
    <div class="mt-4 text-2xl font-bold text-custom-gray"> Add new card</div>
    <div class="text-lg mt-2 text-custom-gray">View Past Transactions, Make New Payments, and Print Receipts</div>
    <div>
    <form action="{{ route('cards.store') }}" method="POST">
    @csrf
    @method('POST')
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">First Name<label>
                <input type="text" placeholder="First Name" value="{{ old('first_name') }}" max="5"
                    name="name" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('name') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('name'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('name') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="last Name">Last Name<label>
                <input type="text" placeholder="Last Name"  value="{{ old('surname') }}" 
                    name="surname" class="mt-2 w-full p-2 rounded border {{ $errors->has('surname') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('surname'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('surname') }}</span>
                    @endif
        </div>
    </div>
    <div>
    <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Card Number<label>
            <input type="text" placeholder="1234 5678 9012 3456" maxlength="19" value="{{ old('card_number') }}"
                name="card_number" id="card_number" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('card_number') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                @if ($errors->has('card_number'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('card_number') }}</span>
                @endif
    </div>
    <div>
    <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Billing Address<label>
            <input type="text" placeholder="Your Address"  value="{{ old('address') }}"
                name="address" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('address') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                @if ($errors->has('address'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('address') }}</span>
                @endif
    </div>
    <div>
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="city">City<label>
                <input type="city" placeholder="City" value="{{ old('city') }}"
                    name="city" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('city') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('city'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('city') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="postal_code">Postal Code (optional)<label>
                <input type="text" placeholder="Postal Code" value="{{ old('postal_code') }}"
                    name="postal_code" class="mt-2 w-full p-2 rounded border {{ $errors->has('postal_code') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('postal_code'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('postal_code') }}</span>
                    @endif
        </div>
    </div>
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">Province/State (optional)<label>
                <input type="text" placeholder="Province/State" value="{{ old('state') }}" max="5"
                    name="state" class="mt-2 w-full p-2 rounded border {{ $errors->has('state') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('state'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('state') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Country<label>
                <select name="country" class="custom-select bg-white mt-2 w-full p-2 pr-4 rounded border {{ $errors->has('state') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
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
                    name="email_address" required  class="mt-2 w-full p-2 rounded border  {{ $errors->has('email_address') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('email_address'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email_address') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="phone_number">Phone Number<label>
                <input type="text" placeholder="Phone number" value="{{ old('phone_number') }}"
                    name="phone_number" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('phone_number') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('phone_number'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('phone_number') }}</span>
                    @endif
        </div>
    </div>
    <div class="flex mt-8">
         <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 bg-green-500 text-white rounded font-bold mt-3">Add new card</button>
    </div>
    </div>

</form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cardNumberInput = document.getElementById('card_number');
    
    // Format card number as user types
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';
        
        // Add spaces after every 4 digits
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        // Limit to 16 digits (19 characters including spaces)
        if (formattedValue.length > 19) {
            formattedValue = formattedValue.substring(0, 19);
        }
        
        e.target.value = formattedValue;
    });
    
    // Handle backspace and delete properly
    cardNumberInput.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' || e.key === 'Delete') {
            let value = e.target.value;
            let cursorPosition = e.target.selectionStart;
            
            // If cursor is at a space, move it back one position
            if (value[cursorPosition - 1] === ' ') {
                e.target.setSelectionRange(cursorPosition - 1, cursorPosition - 1);
            }
        }
    });
    
    // Format existing value on page load
    if (cardNumberInput.value) {
        let value = cardNumberInput.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = '';
        
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        cardNumberInput.value = formattedValue;
    }
});
</script>
@endsection