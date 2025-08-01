@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'cards'])
    <main class="w-screen text-gray-700">
        <div class="w-full sm:w-full lg:w-8/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12">
             <div class="block h-16 w-full bg-white md:hidden lg:hidden"></div>
            <div class="flex justify-between items-center max-w-2xl">
                <div class="text-2xl font-bold text-custom-gray"> Add Card</div>
            </div>
            <div class="text-lg mt-2 flex max-w-2xl text-custom-gray">Its easy, so that you don't have to enter your card everytime</div>
            <div>
            <form action="{{ route('cards.store') }}" method="POST" onsubmit="return checkVisaBeforeSubmit(this)">
    @csrf
    @method('POST')
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">First Name<label>
                <input type="text" placeholder="First Name" value="{{ old('name') }}" max="5"
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
<div class="relative">
  <input
    type="text"
    placeholder="Card  Number"
    max="16"
    value="{{ old('card_number') }}"
    name="card_number"
    required
    id="card_number_input"
    oninput="validateVisaCard(this)"
    class="mt-2 w-full p-2 pr-12 rounded border {{ $errors->has('card_number') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal"
    style="box-sizing: border-box;"
  >
  <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-blue-700">
    <i class="fab fa-cc-visa" style="font-size: 1.5em;"></i>
  </span>
</div>
<span id="card_number_error" class="text-xs tracking-wide text-red-600 font-normal hidden">Only VISA cards are allowed. Please enter a valid VISA card number.</span>
@if ($errors->has('card_number'))
    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('card_number') }}</span>
@endif
    </div>
    <div class="flex flex-row gap-4 mt-4">
  <div class="flex-1">
    <label class="block text-custom-gray font-bold mb-1" for="expiry_month">Expiry Month</label>
    <select id="expiry_month" name="expiry_month" required class="w-full p-2 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
      <option value="">MM</option>
      @for ($m = 1; $m <= 12; $m++)
        <option value="{{ sprintf('%02d', $m) }}">{{ sprintf('%02d', $m) }}</option>
      @endfor
    </select>
  </div>
  <div class="flex-1">
    <label class="block text-custom-gray font-bold mb-1" for="expiry_year">Expiry Year</label>
    <select id="expiry_year" name="expiry_year" required class="w-full p-2 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
      <option value="">YY</option>
      @for ($y = date('y'); $y <= date('y') + 15; $y++)
        <option value="{{ $y }}">{{ $y }}</option>
      @endfor
    </select>
  </div>
  <div class="flex-1">
    <label class="block text-custom-gray font-bold mb-1" for="cvv">CVV
      <span class="ml-1 align-middle relative group cursor-pointer">
        <i class="fas fa-info-circle text-gray-400"></i>
        <span class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-44 bg-gray-800 text-white text-xs rounded py-2 px-3 opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none whitespace-normal text-center">
          3 digits on back of card
        </span>
      </span>
    </label>
    <input type="text" maxlength="4" minlength="3" pattern="\\d{3,4}" id="cvv" name="cvv" required placeholder="CVV" class="w-full p-2 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
  </div>
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
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="postal_code">Postal Code<label>
                <input type="text" placeholder="Postal Code" value="{{ old('postal_code') }}"
                    name="postal_code" class="mt-2 w-full p-2 rounded border {{ $errors->has('postal_code') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('postal_code'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('postal_code') }}</span>
                    @endif
        </div>
    </div>
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">Province/State<label>
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
            <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 bg-white rounded font-bold mt-3 mr-4 text-custom-gray border border-custom-gray w-full sm:w-full lg:w-auto md:w-auto" type="button" onclick="goBack()" id="btns">Cancel</button>
            <button class="px-6 py-4 bg-green-500 text-white rounded font-bold mt-3 w-full sm:w-full lg:w-auto md:w-auto">Add new card</button>
    </div>
    </div>
            </form>

    </div>
            </main>
</div>
<script>
  function goBack() {
      window.history.back();
    }

  function validateVisaCard(input) {
    const value = input.value.replace(/\D/g, '');
    const errorSpan = document.getElementById('card_number_error');
    // VISA: starts with 4, 13 or 16 digits
    const isVisa = /^4(\d{12}|\d{15})$/.test(value);
    if (value.length === 0) {
      errorSpan.classList.add('hidden');
      input.classList.remove('border-red-600');
      input.classList.add('border-custom-green');
      return;
    }
    if (!value.startsWith('4')) {
      errorSpan.classList.remove('hidden');
      input.classList.add('border-red-600');
      input.classList.remove('border-custom-green');
      return;
    }
    if ((value.length === 13 || value.length === 16) && !isVisa) {
      errorSpan.classList.remove('hidden');
      input.classList.add('border-red-600');
      input.classList.remove('border-custom-green');
      return;
    }
    errorSpan.classList.add('hidden');
    input.classList.remove('border-red-600');
    input.classList.add('border-custom-green');
  }

  function checkVisaBeforeSubmit(form) {
    const input = document.getElementById('card_number_input');
    const value = input.value.replace(/\D/g, '');
    const errorSpan = document.getElementById('card_number_error');
    const isVisa = /^4(\d{12}|\d{15})$/.test(value);
    if (!isVisa) {
      errorSpan.classList.remove('hidden');
      input.classList.add('border-red-600');
      input.classList.remove('border-custom-green');
      input.focus();
      return false;
    }
    return true;
  }

  // Auto-advance listeners for expiry fields
  document.addEventListener('DOMContentLoaded', function() {
    const month = document.getElementById('expiry_month');
    const year = document.getElementById('expiry_year');
    const cvv = document.getElementById('cvv');
    if (month && year) {
      month.addEventListener('change', function() {
        if (month.value && year) year.focus();
      });
      year.addEventListener('change', function() {
        if (year.value && cvv) cvv.focus();
      });
    }
  });
</script>
@endsection