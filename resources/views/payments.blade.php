@extends('app')
@section('content')
@include('header')
@include('side-bar', ['activeNavItem' => 'passwords'])
<main class="p-4 sm:ml-64 py-16">
    <div class="mt-4 text-2xl font-bold text-custom-gray">Payments</div>
    <div class="text-lg mt-2 text-custom-gray">View Past Transactions, Make New Payments, and Print Receipts</div>
    <div class="flex justify-between w-full mt-8">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            </span>
            <input type="text" class="block w-96 pl-8 pr-3 py-2 border border-green-500 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500" placeholder="Search">
        </div>
        <div class="flex space-x-4">
            <form action="{{ route('payments.export') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </button>
            </form>
            <a href="#" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">New payment</a>
        </div>
    </div>
    <div class="mt-6 text-custom-gray">You have paid a ZMK 200, 000 in total with 120 payments</div>
    <div class="relative overflow-x-auto mt-4">
        <table class="w-full text-sm text-left text-custom-gray dark:text-gray-400 border-b border-t">
            <thead class="text-xs text-gray-700  bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex">
                            <div class="flex items-center h-5 mr-2">
                                <input id="helper-checkbox" aria-describedby="helper-checkbox-text" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-2 text-sm">
                            Date
                            </div>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Invoice
                    </th>
                    <th scope="col" class="px-6 py-3">
                         Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Method
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex">
                            <div class="flex items-center h-5 mr-2">
                                <input id="helper-checkbox" aria-describedby="helper-checkbox-text" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-2 text-sm">
                            Feb 9, 2023
                            </div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        #F668IO0G
                    </td>
                    <td class="px-6 py-4">
                        Aeronautical Information Publications (AIP)
                    </td>
                    <td class="px-6 py-4">
                        Cash
                    </td>
                    <td class="px-6 py-4 text-right">
                        ZK289,520.00
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex">
                            <div class="flex items-center h-5 mr-2">
                                <input id="helper-checkbox" aria-describedby="helper-checkbox-text" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-2 text-sm">
                            Dec 20, 2022
                            </div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        #RQG8VVEG
                    </td>
                    <td class="px-6 py-4">
                        International Passenger Charge (Includes Aviation...
                    </td>
                    <td class="px-6 py-4">
                        Card
                    </td>
                    <td class="px-6 py-4 text-right">
                        ZK87,345.00
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex">
                            <div class="flex items-center h-5 mr-2">
                                <input id="helper-checkbox" aria-describedby="helper-checkbox-text" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-2 text-sm">
                             June 16, 2022
                            </div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        #O9UX6GNA
                    </td>
                    <td class="px-6 py-4">
                        Domestic Passenger Charge (Includes Aviation Infrastructure
                    </td>
                    <td class="px-6 py-4">
                        Card
                    </td>
                    <td class="px-6 py-4 text-right">
                        ZK2520.00
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex">
                            <div class="flex items-center h-5 mr-2">
                                <input id="helper-checkbox" aria-describedby="helper-checkbox-text" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-2 text-sm">
                                August 9, 2022
                            </div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        #86E2EA5B
                    </td>
                    <td class="px-6 py-4">
                        Fuel Throughput Concession
                    </td>
                    <td class="px-6 py-4">
                        Card
                    </td>
                    <td class="px-6 py-4 text-right">
                        ZK187,345.00
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="flex">
                            <div class="flex items-center h-5 mr-2">
                                <input id="helper-checkbox" aria-describedby="helper-checkbox-text" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-none dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-none dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div class="ml-2 text-sm">
                                March 15, 2022
                            </div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                        #G6GX6LSR
                    </td>
                    <td class="px-6 py-4">
                        Concession Business Lounges
                    </td>
                    <td class="px-6 py-4">
                        Card
                    </td>
                    <td class="px-6 py-4 text-right">
                        ZK20,357,345.00
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
</div>
<div class="flex justify-center mt-6">
  <nav class="inline-flex space-x-2">
    <a href="#" class="px-4 py-2 rounded text-custom-gray border border-custom-gray font-normal">
      Previous
    </a>
    <a href="#" class="px-4 py-2 rounded text-custom-gray border border-custom-gray font-normal">
      1
    </a>
    <a href="#" class="px-4 py-2 rounded text-white bg-custom-gray border border-custom-gray font-bold">
      2
    </a>
    <a href="#" class="px-4 py-2 rounded text-custom-gray border border-custom-gray font-normal">
      3
    </a>
    <a href="#" class="px-4 py-2 rounded text-custom-gray border border-custom-gray font-normal">
      Next
    </a>
  </nav>
</div>
<div class="w-ful text-center mt-4">Showing 1 to 30 of 5 pages</div>
</main>
@endsection