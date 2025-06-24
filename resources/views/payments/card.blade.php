@extends('app')
@section('content')
@include('header')
@include('side-bar', ['activeNavItem' => 'payments'])
<main class="py-16 flex justify-center">
    <div class="w-8/12">
    <div class="mt-4 text-2xl font-bold text-custom-gray"> Add new card</div>
    <div class="text-lg mt-2 text-custom-gray">View Past Transactions, Make New Payments, and Print Receipts</div>
    <div>
    <form action="{{ route('payments.checkout') }}" method="GET">
    @csrf
    @method('GET')
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">First Name<label>
                <input type="text" placeholder="First Name" value="{{ old('first_name') }}" max="5"
                    name="first_name" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('first_name') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('first_name'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('first_name') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="last Name">Last Name<label>
                <input type="text" placeholder="Last Name"  value="{{ old('last_name') }}" 
                    name="last_name" class="mt-2 w-full p-2 rounded border {{ $errors->has('last_name') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('last_name'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('last_name') }}</span>
                    @endif
        </div>
    </div>
    <div>
    <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Card Number<label>
            <input type="text" placeholder="Card  Number" max="16" value="{{ old('card_number') }}"
                name="card_number" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('card_number') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
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
    <div class="flex mt-5">
        <div class="flex items-center h-5 mr-2">
            <input aria-describedby="helper-checkbox-text" name="save" type="checkbox" value="1" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded-2 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-none dark:bg-gray-700 dark:border-gray-600" checked>
        </div>
        <div class="ml-1 text-sm">
        Save card for later reuse
        </div>
    </div>
    <div class="flex mt-8">
        <button class="px-6 py-4 bg-white focus:ring-4 focus:ring-blue-300  rounded font-bold mt-3 mr-4 text-custom-gray border border-custom-gray" id="btn">Cancel</button>
         <button class="px-6 py-4 bg-green-500 focus:ring-4 focus:ring-blue-300  text-white rounded font-bold mt-3">Continue</button>
    </div>
</form>
    </div>
</main>
@endsection