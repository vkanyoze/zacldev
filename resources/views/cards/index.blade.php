@extends('app')
@section('content')
@include('header')
@include('side-bar', ['activeNavItem' => 'cards'])
<main class="p-4 sm:ml-64 py-16">
    <div class="mt-4 text-2xl font-bold text-custom-gray">Cards</div>
    <div class="text-lg mt-2 text-custom-gray">View Past Transactions, Make New Payments, and Print Receipts</div>
    <div class="flex justify-between w-full mt-8">
        <form class="max-w-2xl" action="{{ route('cards.search') }}" method="GET">   
            @csrf
            <label for="default-search" class="mb-2 w-96 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="search" id="default-search" value="{{ old('query') }}"  name="query"  class="block w-full p-4 pl-10 text-sm text-gray-900 border border-custom-green rounded-lg bg-gray-50 focus:ring-blue-500  focus:outline-none  focus:border-blue-500" placeholder="Search cards..">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-custom-gray hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-4 py-2 ">Search</button>
            </div>
        </form>


       <a href="{{ route('cards.create') }}"><button class="px-6 py-4 bg-green-500 text-white rounded-md">Add new card</button></a>
    </div>
    <div class="mt-6 text-custom-gray">You have {{ $cards->total() }} cards in total</div>
    <div class="relative overflow-x-auto mt-4">
        <table class="w-full text-sm text-left text-custom-gray dark:text-gray-400 border-b border-t">
            <thead class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Card Number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($cards as $card)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $card->name }} {{ $card->surname }}
                    </th>
                    <td class="px-6 py-4">
                        {{ substr_replace($card->card_number, '************', 0, 12) }}
                    </td>
                    <td class="px-6 py-4">
                      @if(!empty(trim($card->city))) {{ $card->state }}, @endif
                      @if(!empty(trim($card->city))) {{ $card->city }}, @endif
                      {{ $card->address }}
                      @if(!empty(trim($card->postal_code))) {{ $card->postal_code }}, @endif
                      @if(!empty(trim($card->country))) {{ $card->country }} @endif
                    </td>
                    <td class="px-6 py-4">
                      {{ $card->email_address }} 
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('cards.payment', $card->id) }}" method="GET" style="display: inline-block;">
                                    @csrf
                                    @method('GET')
                                    @if (request()->has('_token'))
                                        <input type="hidden" name="_token" value="{{ request()->input('_token') }}">
                                    @endif
                                    <button type="submit" class="btn btn-danger text-custom-green mr-2">Pay</button>
                                </form>
                        <form action="{{ route('cards.edit', $card->id) }}" method="GET" style="display: inline-block;">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn text-blue-600 mr-2">Edit</button>
                                </form>
                                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-red-600">Delete</button>
                        </form>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
<div class="flex justify-center mt-6">
  <nav class="inline-flex space-x-2">
        @if (!$cards->onFirstPage())
            <a href="{{ $cards->previousPageUrl() }}{{ request()->has('query') ? ',query=' . request()->query('query') : '' }}" class="px-4 py-2 rounded focus:ring-4 focus:ring-blue-300  text-custom-gray border border-custom-gray font-normal">Prev</a>
        @endif
        @foreach ($cards->getUrlRange(1, $cards->lastPage()) as $page => $url)
            @if ($page == $cards->currentPage())
                <a href="{{ $url }}{{ request()->has('query') ? ',query=' . request()->query('query') : '' }}" class="px-4 py-2 focus:ring-4 focus:ring-blue-300  rounded text-white bg-custom-gray border border-custom-gray font-bold"> {{ $page }} </a>
            @else
                <a href="{{ $url }}{{ request()->has('query') ? ',query=' . request()->query('query') : '' }}" class="px-4 py-2 focus:ring-4 focus:ring-blue-300 rounded text-custom-gray border border-custom-gray font-normal">{{ $page }}</a>
            @endif
        @endforeach
        @if ($cards->hasMorePages())
            <a href="{{ $cards->nextPageUrl() }}{{ request()->has('query') ? ',query=' . request()->query('query') : '' }}" class="px-4 py-2 rounded focus:ring-4 focus:ring-blue-300  text-custom-gray border border-custom-gray font-normal">Next</a>
        @endif
  </nav>
</div>
<div class="w-ful text-center mt-4"><p>Showing {{ $cards->firstItem() }} to {{ $cards->lastItem() }} of {{ $cards->total() }} results</p></div>
</main>
@if(session('success'))
<div id="alert" class="text-white fixed drop-shadow-md top-4 right-4 w-96 z-20 flex p-4 mb-4 border-l-4 bg-custom-green" role="alert">
    <svg class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
    <div class="ml-3 font-medium">
        {{ session('success') }} 
    </div>
    <button type="button" onclick="closeAlert()" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"  data-dismiss-target="#alert-border-3" aria-label="Close">
      <span class="sr-only">Dismiss</span>
      <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>
</div>
    <script>
        const alertBox = document.getElementById('alert');
        close.addEvent
        if (alertBox) {
            setTimeout(() => {
                alertBox.remove();
            }, 5000);
        }
        function closeAlert() {
            var alertBox = document.getElementById("alert");
            alertBox.style.display = "none";
        }
    </script>
@endif

@endsection