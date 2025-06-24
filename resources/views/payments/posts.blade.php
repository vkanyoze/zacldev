@extends('new-app')
@section('content')
@include('new-header')
<div class="flex-grow flex">
    @include('new-side-bar',  ['activeNavItem' => 'payments'])
    <main class="w-screen text-gray-700 pt-16 md:pt-0 lg:pt-0">
        <div class="w-full sm:w-full lg:w-8/12 md:w-full p-6 sm:p-6 md:p-8 lg:p-12">
            <div class="flex justify-between items-center max-w-2xl">
                <div class="text-2xl font-bold text-custom-gray items-center"> 
                    New payment
                </div>
                <div class="font-semibold text-base">Step 1 of 3</div>
            </div>
            <div class="text-lg mt-2 flex max-w-2xl text-custom-gray">Add your invoice number, add amount , and select service to be paid.</div>
            <div>
                <form action="{{ route('payments.card') }}" method="GET">
                    @csrf
                    @method('GET')
                    <label class="block text-custom-gray font-bold mt-4 max-w-2xl" for="invoice_reference">Invoice Number<label>
                            <input type="text" placeholder="Invoice Number"
                                name="invoice_reference" value="{{ old('invoice_reference') }}" required  class="mt-2 w-full p-2 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
                                @if ($errors->has('invoice_reference'))
                                    <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('invoice_reference') }}</span>
                                @endif
                    </div>
                    <div class="flex gap-3 items-center max-w-2xl">
                        <div class="flex flex-col">
                            <label class="block text-custom-gray font-bold mt-3" for="currency">Currency</label>
                            <select name="currency" id="currency" class="custom-select mt-2 md:mt-0 w-full md:w-auto p-2 pr-8 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
                                <option value="USD" selected>USD</option>
                                <option value="ZAR">ZAR</option>
                                <option value="ZMW">ZMW</option>
                            </select>
                            @if ($errors->has('currency'))
                                <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('currency') }}</span>
                            @endif
                        </div>

                        <div class="flex flex-col grow">
                            <label class="block text-custom-gray font-bold mt-3" for="amount_spend">Amount</label>
                            <input type="number" placeholder="Amount to be paid" min="1" value="{{ old('amount_spend') }}"
                                name="amount_spend" required class="mt-2 md:mt-0 w-full md:w-auto p-2 rounded border border-custom-green focus:outline-none focus:border-blue-600 font-normal">
                            @if ($errors->has('amount_spend'))
                                <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('amount_spend') }}</span>
                            @endif
                        </div>
                            </div>
                            <div id="con">
                            <div class="max-w-2xl" id="sub">
                        <label for="large" class="block text-custom-gray font-bold mt-3">Service</label>
                        <select id="subcategory" name="service_type" class="custom-select text-custom-gray w-full px-2 py-3 mt-2 text-base  border border-custom-green rounded bg-white focus:outline-none focus:border-blue-600">
                        <option selected value="">Choose service you want to pay</option>
                        <option value="advertising" class="text-custom-gray">Advertising</option>
                        <option value="aeronautical_information_publications">Aeronautical Information Publications (AIP)</option>
                        <option value="baggage_wrapping">Baggage Wrapping</option>
                        <option value="concession_restaurants">Concession Restaurants</option>
                        <option value="concession_duty_free">Concession Duty Free</option>
                        <option value="concession_car_park">Concession Car Park</option>
                        <option value="concession_business_lounges">Concession Business Lounges</option>
                        <option value="concession_catering_services">Concession Catering Services</option>
                        <option value="domestic_landing_charge">Domestic Landing Charge</option>
                        <option value="domestic_parking">Domestic Parking</option>
                        <option value="domestic_passenger_charge">Domestic Passenger Charge (Includes Aviation Infrastructure Development Fee, Aviation Security Charge, Air Passenger Service Charge)</option>
                        <option value="domestic_navigation_charges">Domestic Navigation Charge</option>
                        <option value="equipment_hire">Equipment Hire</option>
                        <option value="fuel_throughput_concession">Fuel Throughput Concession</option>
                        <option value="ground_handling_charge">Ground Handling Charge</option>
                        <option value="installations">Installations</option>
                        <option value="international_navigation_charges">International Navigation Charge</option>
                        <option value="international_landing_charge">International Landing Charge</option>
                        <option value="international_parking">International Parking</option>
                        <option value="international_passenger_charge">International Passenger Charge (Includes Aviation Infrastructure Development Fee, Aviation Security Charge, Air Passenger Service Charge)</option>
                        <option value="invoice_payment">Invoice Payment</option>
                        <option value="minimum_guarantee">Minimum Guarantee</option>
                        <option value="miscellaneous">Miscellaneous (IDs,Permits Etc)</option>
                        <option value="over_flight_navigation_charge">Over Flight Navigation Charge</option>
                        <option value="payment_on_account">Payment On Account</option>
                        <option value="rental_kiosks">Rental Kiosks</option>
                        <option value="rental_offices">Rental Offices</option>
                        <option value="rental_warehouses">Rental Warehouses</option>
                        <option value="sucharge">Surcharge</option>
                        <option value="water_and_electricity">Water & Electricity</option>
                        </select>
                        @if ($errors->has('service_type'))
                            <span class="text-xs tracking-wide text-red-600 font-normal">{{ $errors->first('service_type') }}</span>
                        @endif
                    </div>
                        <div class="max-w-2xl" id="sub_2">
                        <label for="large" class="block text-custom-gray font-bold mt-3">Service 2 (optional)</label>
                        <select id="subcategory" name="service_type_1" class="custom-select text-custom-gray w-full px-2 py-3 mt-2 text-base border border-custom-green rounded bg-white focus:outline-none focus:border-blue-600">
                        <option selected value="">Choose service you want to pay</option>
                        <option value="advertising">Advertising</option>
                        <option value="aeronautical_information_publications">Aeronautical Information Publications (AIP)</option>
                        <option value="baggage_wrapping">Baggage Wrapping</option>
                        <option value="concession_restaurants">Concession Restaurants</option>
                        <option value="concession_duty_free">Concession Duty Free</option>
                        <option value="concession_car_park">Concession Car Park</option>
                        <option value="concession_business_lounges">Concession Business Lounges</option>
                        <option value="concession_catering_services">Concession Catering Services</option>
                        <option value="domestic_landing_charge">Domestic Landing Charge</option>
                        <option value="domestic_parking">Domestic Parking</option>
                        <option value="domestic_passenger_charge">Domestic Passenger Charge (Includes Aviation Infrastructure Development Fee, Aviation Security Charge, Air Passenger Service Charge)</option>
                        <option value="domestic_navigation_charges">Domestic Navigation Charge</option>
                        <option value="equipment_hire">Equipment Hire</option>
                        <option value="fuel_throughput_concession">Fuel Throughput Concession</option>
                        <option value="ground_handling_charge">Ground Handling Charge</option>
                        <option value="installations">Installations</option>
                        <option value="international_navigation_charges">International Navigation Charge</option>
                        <option value="international_landing_charge">International Landing Charge</option>
                        <option value="international_parking">International Parking</option>
                        <option value="international_passenger_charge">International Passenger Charge (Includes Aviation Infrastructure Development Fee, Aviation Security Charge, Air Passenger Service Charge)</option>
                        <option value="invoice_payment">Invoice Payment</option>
                        <option value="minimum_guarantee">Minimum Guarantee</option>
                        <option value="miscellaneous">Miscellaneous (IDs,Permits Etc)</option>
                        <option value="over_flight_navigation_charge">Over Flight Navigation Charge</option>
                        <option value="payment_on_account">Payment On Account</option>
                        <option value="rental_kiosks">Rental Kiosks</option>
                        <option value="rental_offices">Rental Offices</option>
                        <option value="rental_warehouses">Rental Warehouses</option>
                        <option value="sucharge">Surcharge</option>
                        <option value="water_and_electricity">Water & Electricity</option>
                        </select>
                    </div>
                    <div class="max-w-2xl" id="sub">
                        <label for="large" class="block text-custom-gray font-bold mt-3">Service 3 (optional)</label>
                        <select placeholder="select items" id="subcategory" name="service_type_2" class="custom-select text-custom-gray w-full px-2 py-3 mt-2 text-base border border-custom-green rounded bg-white focus:outline-none focus:border-blue-600">
                        <option selected value="">Choose service you want to pay</option>
                        <option value="advertising">Advertising</option>
                        <option value="advertising">Advertising</option>
                        <option value="aeronautical_information_publications">Aeronautical Information Publications (AIP)</option>
                        <option value="baggage_wrapping">Baggage Wrapping</option>
                        <option value="concession_restaurants">Concession Restaurants</option>
                        <option value="concession_duty_free">Concession Duty Free</option>
                        <option value="concession_car_park">Concession Car Park</option>
                        <option value="concession_business_lounges">Concession Business Lounges</option>
                        <option value="concession_catering_services">Concession Catering Services</option>
                        <option value="domestic_landing_charge">Domestic Landing Charge</option>
                        <option value="domestic_parking">Domestic Parking</option>
                        <option value="domestic_passenger_charge">Domestic Passenger Charge (Includes Aviation Infrastructure Development Fee, Aviation Security Charge, Air Passenger Service Charge)</option>
                        <option value="domestic_navigation_charges">Domestic Navigation Charge</option>
                        <option value="equipment_hire">Equipment Hire</option>
                        <option value="fuel_throughput_concession">Fuel Throughput Concession</option>
                        <option value="ground_handling_charge">Ground Handling Charge</option>
                        <option value="installations">Installations</option>
                        <option value="international_navigation_charges">International Navigation Charge</option>
                        <option value="international_landing_charge">International Landing Charge</option>
                        <option value="international_parking">International Parking</option>
                        <option value="international_passenger_charge">International Passenger Charge (Includes Aviation Infrastructure Development Fee, Aviation Security Charge, Air Passenger Service Charge)</option>
                        <option value="invoice_payment">Invoice Payment</option>
                        <option value="minimum_guarantee">Minimum Guarantee</option>
                        <option value="miscellaneous">Miscellaneous (IDs,Permits Etc)</option>
                        <option value="over_flight_navigation_charge">Over Flight Navigation Charge</option>
                        <option value="payment_on_account">Payment On Account</option>
                        <option value="rental_kiosks">Rental Kiosks</option>
                        <option value="rental_offices">Rental Offices</option>
                        <option value="rental_warehouses">Rental Warehouses</option>
                        <option value="sucharge">Surcharge</option>
                        <option value="water_and_electricity">Water & Electricity</option>
                        </select>
                    </div>
                </div>
                    <div class="flex mt-8">
                        <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300 bg-white rounded font-bold mt-3 mr-4 text-custom-gray border border-custom-gray w-full sm:w-full lg:w-auto md:w-auto" type="button" onclick="goBack()" id="btns">Cancel</button>
                        <button class="px-6 py-4 bg-green-500 text-white rounded font-bold mt-3 w-full sm:w-full lg:w-auto md:w-auto">Continue</button>
                    </div>
                    <span class="hidden text-xs tracking-wide text-red-600 font-normal" id="warning">You can only add 3 services at a time</span>
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