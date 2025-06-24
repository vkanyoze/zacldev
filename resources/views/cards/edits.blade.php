@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'cards'])
    <main class="w-screen text-gray-700">
        <div class="w-full sm:w-full lg:w-8/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12">
             <div class="block h-16 w-full bg-white md:hidden lg:hidden"></div>
            <div class="flex justify-between items-center max-w-2xl">
                <div class="text-2xl font-bold text-custom-gray"> Edit Card</div>
            </div>
            <div class="text-lg mt-2 flex max-w-2xl text-custom-gray">Keep your information updated</div>
            <div>
            <form action="{{ route('cards.update', $card->id) }}" method="POST">
    @csrf
    @method('POST')
        <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">First Name<label>
                <input type="text" placeholder="First Name" value="{{ $card->name }}" max="5"
                    name="name" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('name') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('name'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('name') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="last Name">Last Name<label>
                <input type="text" placeholder="Last Name"  value="{{ $card->surname }}" 
                    name="surname" class="mt-2 w-full p-2 rounded border {{ $errors->has('surname') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('surname'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('surname') }}</span>
                    @endif
        </div>
    </div>
    <div>
    <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Card Number<label>
            <input type="text" placeholder="Card  Number" max="16" value="{{ $card->card_number }}"
                name="card_number" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('card_number') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                @if ($errors->has('card_number'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('card_number') }}</span>
                @endif
    </div>
    <div>
    <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Billing Address<label>
            <input type="text" placeholder="Your Address"  value="{{ $card->address }}"
                name="address" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('address') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                @if ($errors->has('address'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('address') }}</span>
                @endif
    </div>
    <div>
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="city">City<label>
                <input type="city" placeholder="City" value="{{ $card->city }}"
                    name="city" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('city') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('city'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('city') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="postal_code">Postal Code<label>
                <input type="text" placeholder="Postal Code" value="{{ $card->postal_code }}"
                    name="postal_code" class="mt-2 w-full p-2 rounded border {{ $errors->has('postal_code') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('postal_code'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('postal_code') }}</span>
                    @endif
        </div>
    </div>
    <div class="flex max-w-2xl">
        <div class="flex-1 w-64 mr-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="expiry_date">Province/State<label>
                <input type="text" placeholder="Province/State" value="{{ $card->state }}" max="5"
                    name="state" class="mt-2 w-full p-2 rounded border {{ $errors->has('state') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('state'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('state') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="card_number">Country<label>
                <select name="country" class="custom-select bg-white mt-2 w-full p-2 pr-4 rounded border {{ $errors->has('state') ? 'border-red-600' : 'border-custom-green' }} focus:outline-none focus:border-blue-600 font-normal">
                    <option >Select Country</option>
                    @foreach ($countries as $isoCode => $countryName)
                        <option value="{{ $isoCode }}" {{ $card->country == $isoCode ? 'selected' : '' }}>
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
                <input type="email" placeholder="Email Address" value="{{ $card->email_address }}"
                    name="email_address" required  class="mt-2 w-full p-2 rounded border  {{ $errors->has('email_address') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('email_address'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('email_address') }}</span>
                    @endif
        </div>
        <div class="flex-1 w-32 ml-2">
                <label class="block text-custom-gray font-bold mt-3  max-w-2xl" for="phone_number">Phone Number<label>
                <input type="text" placeholder="Phone number" value="{{ $card->phone_number }}"
                    name="phone_number" required  class="mt-2 w-full p-2 rounded border {{ $errors->has('phone_number') ? 'border-red-600' : 'border-custom-green' }}  focus:outline-none focus:border-blue-600 font-normal">
                    @if ($errors->has('phone_number'))
                        <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('phone_number') }}</span>
                    @endif
        </div>
    </div>


    <div>
    <div class="flex mt-8">
                    <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 bg-white rounded font-bold mt-3 mr-4 text-custom-gray border border-custom-gray w-full sm:w-full lg:w-auto md:w-auto" type="button" onclick="goBack()" id="btns">Cancel</button>

            <button class="px-6 py-4 bg-green-500 text-white rounded font-bold mt-3 w-full sm:w-full lg:w-auto md:w-auto">Continue</button>
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
</script>
@endsection