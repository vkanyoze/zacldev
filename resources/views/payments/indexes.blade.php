@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'payments'])
    <main class="w-screen text-gray-700 pt-16 md:pt-0 lg:pt-0">
        <div class="w-full sm:w-full lg:w-11/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12 lg:pt-3">
        <div class="flex justify-between items-center max-w-full">
                <div class="text-2xl font-bold text-custom-gray">Payments</div>
                <div class="font-semibold text-base">
                        <a href="{{ route('payments.create') }} " class="flex md:hidden lg:hidden"><button class="px-4 py-3 bg-green-500 text-base text-white rounded-md">New payment</button></a>
                </div>
        </div>
    <div class="text-lg mt-2 text-custom-gray">View Past Transactions, Make New Payments, and Print Receipts</div>
    <div class="justify-between w-full mt-8 hidden md:flex lg:flex">
      <form class="max-w-2xl" action="{{ route('payments.search') }}" method="GET">   
            @csrf
            <label for="default-search" class="mb-2 w-96 text-sm font-medium text-gray-900 sr-only ">Search</label>
            <div class="relative w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="search" id="default-search" value="{{ old('query') }}"  name="query"  class="block w-full p-4 pl-10 text-sm text-gray-900 border border-custom-green rounded-lg bg-gray-50 focus:ring-blue-500  focus:outline-none  focus:border-blue-500" placeholder="Search cards..">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-custom-gray hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-4 py-2">Search</button>
            </div>
        </form>
        <div class="flex gap-8">
            <form action="{{ route('payments.export') }}" method="GET" class="inline">
                <button type="submit" class="px-6 py-4 bg-blue-500 text-white rounded-md hover:bg-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Payments
                </button>
            </form>
            &nbsp; &nbsp;
            <a href="{{ route('payments.create') }}" class="px-6 py-4 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New payment
            </a>
        </div>
    </div>
    <div class="mt-6 text-custom-gray">You have made {{ $payments->total() }} payments in total</div>
    <div class="relative overflow-x-auto mt-4">
        <table class="w-full text-sm text-left text-custom-gray  border-b border-t">
            <thead class="text-xs text-gray-700  bg-gray-50 border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3 hidden md:flex lg:flex">
                         DATE
                    </th>
                    <th scope="col" class="px-6 py-3 uppercase">
                        MESSAGE
                    </th>
                    <th scope="col" class="px-6 py-3">
                         SERVICES(S)
                    </th>
                    <th scope="col" class="px-6 py-3 hidden md:flex lg:flex">
                        STATUS
                    </th>
                    <th scope="col" class="px-6 py-3 text-right ">
                        PAID
                    </th>
                    <th scope="col" class="px-6 py-3 text-right hidden md:flex lg:flex">
                        ACTION
                    </th>

                </tr>
            </thead>
            <tbody>
                 @foreach ($payments as $payment)
                <tr class=" align-top bg-white border-b  hover:bg-gray-50 ">
                    <td scope="row" class="px-6 py-4 align-top font-medium text-gray-900 whitespace-nowrap  hidden md:flex lg:flex">
                
                                {{ $payment->created_at->diffForHumans() }}
      
                    </td>
                    <td class="px-6 py-4 align-top">
                        <div class="flex-col flex md:hidden lg:hidden">
                            <div class="p-0  text-left {{ $payment->status == 'ACCEPT' ? 'text-green-500' : 'text-red-500' }} flex md:hidden lg:hidden">
                                {{ $payment->status }} 
                            </div>
                            <div>{{$payment->description}} </div>
                        </div>
                        <div class="flex-col hidden md:flex lg:flex">
                            <div>{{$payment->description}} </div>
                            <div class="text-sm text-gray-400">Inv# {{$payment->invoice_reference}} </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 align-baseline">
                                @php
                                    $variable = $payment->service_type;
                                    $array = explode('_', $variable);
                                    $sentanceCase = ucwords(implode(' ', $array));
                                @endphp

                                <div class="flex flex-col">
                                <div>{{ $sentanceCase }}</div>
                                <div class="text-sm text-gray-400 flex md:hidden lg:hidden">Inv# {{$payment->invoice_reference}} </div>
                                </div>
                       
                    </td>
                    <td class="px-6 py-4 align-top {{ $payment->status == 'ACCEPT' ? 'text-green-500' : 'text-red-500' }} hidden md:flex lg:flex">
                       {{ $payment->status }} 
                    </td>
                    <td class="px-6 py-4 text-right align-baseline">
                        <div class="flex flex-col text-right">
                            <div class="block md:hidden lg:hidden text-gray-400 pb-2 text-xs text-right">
                                {{ $payment->created_at->diffForHumans() }} 
                            </div>
                            <div>{{ $payment->currency }} {{$payment->amount_spend}}</div>
                        <div  class="flex md:hidden lg:hidden">
                            @if ($payment->status == 'ACCEPT')
                            <form action="{{ route('payments.invoice', $payment->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn text-blue-600 mr-0 text-right mt-2">Download Receipt</button>
                            </form>
                        @endif
                        </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right hidden md:flex lg:flex align-baseline">
                        @if ($payment->status == 'ACCEPT')
                            <form action="{{ route('payments.invoice', $payment->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn text-blue-600 mr-2">Print</button>
                            </form>
                        @endif
                        @if ($payment->status !== 'ACCEPT')
                          <div class=" text-center">-testing-</div>
                        @endif
                    </td>
                </tr>
                 @endforeach
            </tbody>
        </table>
</div>
<div class="flex justify-center mt-6">
  <nav class="inline-flex space-x-2">
        @if (!$payments->onFirstPage())
            <a href="{{ $payments->previousPageUrl() }}" class="px-4 py-2 rounded focus:ring-4 focus:ring-blue-300  text-custom-gray border border-custom-gray font-normal">Prev</a>
        @endif
        @foreach ($payments->getUrlRange(1, $payments->lastPage()) as $page => $url)
            @if ($page == $payments->currentPage())
                <a href="{{ $url }}{{ request()->has('query') ? '?query=' . request()->query('query') : '' }}" class="px-4 py-2 focus:ring-4 focus:ring-blue-300  rounded text-white bg-custom-gray border border-custom-gray font-bold"> {{ $page }} </a>
            @else
                <a href="{{ $url }}{{ request()->has('query') ? '?query=' . request()->query('query') : '' }}" class="px-4 py-2 focus:ring-4 focus:ring-blue-300 rounded text-custom-gray border border-custom-gray font-normal">{{ $page }}</a>
            @endif
        @endforeach
        @if ($payments->hasMorePages())
            <a href="{{ $payments->nextPageUrl() }}{{ request()->has('query') ? '?query=' . request()->query('query') : '' }}" class="px-4 py-2 rounded focus:ring-4 focus:ring-blue-300  text-custom-gray border border-custom-gray font-normal">Next</a>
        @endif
  </nav>
</div>
<div class="w-ful text-center mt-4"><p>Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} results</p></div>
</div>
          
    </main>
</div>
@endsection