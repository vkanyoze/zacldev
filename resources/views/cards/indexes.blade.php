@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'cards'])
    <main class="w-screen text-gray-700  pt-4">
        <div class="w-full sm:w-full lg:w-11/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12 lg:pt-3 mt-10 lg:mt-0 md:mt-0">
        <div class="flex justify-between items-center max-w-full">
                <div class="text-2xl font-bold text-custom-gray">Cards</div>
                <div class="font-semibold text-base">
                        <a href="{{ route('cards.create') }} " class="flex md:hidden lg:hidden"><button class="px-4 py-3 bg-green-500 text-base text-white rounded-md">New card</button></a>
                </div>
        </div>
    <div class="text-lg mt-2 text-custom-gray">View saved cards, add cards to save time with next payments</div>
        <div class="justify-between w-full mt-8 hidden md:flex lg:flex">
        <form class="max-w-2xl" action="{{ route('cards.search') }}" method="GET">   
            @csrf
            <label for="default-search" class="mb-2 w-96 text-sm font-medium text-gray-900 sr-only ">Search</label>
            <div class="relative w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="search" id="default-search" value="{{ old('query') }}"  name="query"  class="block w-full p-4 pl-10 text-sm text-gray-900 border border-custom-green rounded-lg bg-gray-50 focus:ring-blue-500  focus:outline-none  focus:border-blue-500" placeholder="Search cards..">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-custom-gray hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-4 py-2 ">Search</button>
            </div>
        </form>


       <a href="{{ route('cards.create') }}"><button class="px-6 py-4 bg-green-500 text-white rounded-md">Add new card</button></a>
    </div>
    <div class="mt-6 text-custom-gray">You have {{ $cards->total() }} cards in total</div>
    <div class="relative overflow-x-auto mt-4">
        <table class="w-full text-sm text-left text-custom-gray  border-b border-t">
            <thead class="text-xs text-gray-700  bg-gray-50  border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 hidden md:flex lg:flex">
                        Card Number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Address
                    </th>
                    <th scope="col" class="px-6 py-3 hidden md:flex lg:flex">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($cards as $card)
                <tr class="bg-white border-b  hover:bg-gray-50 ">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        <div class="flex-col flex text-custom-gray">
                            <div class="p-0  text-left flex text-custom-gray">
                                {{ $card->name }} {{ $card->surname }}
                            </div>
                            <div class="flex md:hidden lg:hidden text-gray-500">{{$card->email_address}} </div>
                            <div class="flex md:hidden lg:hidden text-gray-500 mt-2">{{ substr_replace($card->card_number, '************', 0, 12) }}</div>
                        </div>
                    </th>
                    <td class="px-6 py-4 hidden md:flex lg:flex">
                        <div class="flex py-4">
                        {{ substr_replace($card->card_number, '************', 0, 12) }}
</div>
                    </td>
                    <td class="px-6 py-4">
                      @if(!empty(trim($card->city))) {{ $card->state }}, @endif
                      @if(!empty(trim($card->city))) {{ $card->city }}, @endif
                      {{ $card->address }}
                      @if(!empty(trim($card->postal_code))) {{ $card->postal_code }}, @endif
                      @if(!empty(trim($card->country))) {{ $card->country }} @endif
                    </td>
                    <td class="px-6 py-4 hidden md:flex lg:flex">
                      {{ $card->email_address }} 
                    </td>
                    <td class="px-6 py-4">
                        <div class="hidden md:flex lg:flex">
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
                    </div>
                        <div class="flex-col  flex md:hidden lg:hidden gap-3">
                        <form action="{{ route('cards.payment', $card->id) }}" method="GET" style="display: inline-block;">
                                    @csrf
                                    @method('GET')
                                    @if (request()->has('_token'))
                                        <input type="hidden" name="_token" value="{{ request()->input('_token') }}">
                                    @endif
                                    <button type="submit" class="btn btn-danger text-custom-green ">Pay</button>
                                </form>
                        <form action="{{ route('cards.edit', $card->id) }}" method="GET" style="display: inline-block;">
                                    @csrf
                                    @method('GET')
                                    <button type="submit" class="btn text-blue-600 mt-2">Edit</button>
                                </form>
                                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-red-600 mt-2">Delete</button>
                        </form>
                    </div>
                        
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
</div>
@endsection