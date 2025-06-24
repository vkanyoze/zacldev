@extends('app')
@section('content')
@include('header')
@include('side-bar', ['activeNavItem' => 'cards'])
<main class="py-16 flex justify-center">
    <div class="w-8/12">
    <div class="mt-4 text-2xl font-bold text-custom-gray"> Add CVV</div>
    <div class="text-lg mt-2 text-custom-gray">To validate your transaction add cvv</div>
    <div>
    <form action="{{ route('cards.cvv') }}" method="GET">
    @csrf
    @method('GET')
    <label class="block text-custom-gray font-bold mt-4 max-w-2xl" for="invoice_reference">CVV<label>
            <input type="password" placeholder="your cvv"
                name="cvv" value="{{ old('cvv') }}" required  class="mt-2 w-full p-2 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
                @if ($errors->has('cvv'))
                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('cvv') }}</span>
                @endif
    </div>
    <div class="flex mt-8">
         <button type="submit" class="px-6 py-4 focus:ring-4 focus:ring-blue-300 bg-green-500 text-white rounded font-bold mt-3">Continue</button>
    </div>
    <span class="hidden text-xs tracking-wide text-red-600 font-normal" id="warning">You can only add 3 services at a time</span>
    </div>
</form>
</main>
@endsection