<?php

define ('HMAC_SHA256', 'sha256');

function sign ($params) {
  return signData(buildDataToSign($params), env('SECRET_KEY'));
}

function signData($data, $secretKey) {
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

function buildDataToSign($params) {
        $signedFieldNames = explode(",",$params["signed_field_names"]);
        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }
        return commaSeparate($dataToSign);
}

function commaSeparate ($dataToSign) {
    return implode(",",$dataToSign);
}

?>
@extends('app')
@section('content')
@include('header')
@include('side-bar', ['activeNavItem' => 'cards'])
<main class="py-16 flex justify-center">
    <div class="w-8/12">
    <div class="mt-4 text-2xl font-bold text-custom-gray">Confirm transaction</div>
    <div class="text-lg mt-2 text-custom-gray">You’re about to make a payment please check the details before proceeding</div>
    <div>
    <div class="flex max-w-2xl mt-5">
        <div class="w-32 mr-2 text-lighter-gray">
            Amount
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
            USD {{ $invoice['amount_spend']}}
        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            To
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
            Zambia Airports Corporation Limited 
        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            From
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
            {{ $card['full_name'] }}
        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            For
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
            @php
                $variable = $invoice['service_type'];
                $array = explode('_', $variable);
                $sentanceCase = ucwords(implode(' ', $array));
            @endphp

            {{ $sentanceCase }}

        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            Invoice number
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
            {{ $invoice['invoice_reference']}}
        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            Card number
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
             {{ str_repeat('*', 12) . substr($card['card_number'], -4);}}
        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            Due
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
            {{ \Carbon\Carbon::now()->format('j M, Y') }}
        </div>
    </div>
    <div class="flex max-w-2xl mt-4">
        <div class="w-32 mr-2 text-lighter-gray">
            Email
        </div>
        <div class="flex-1 w-64 ml-2 font-bold text-custom-gray">
             {{ $card['email_address']}}
        </div>
    </div>
    <form action="{{ env('CYBERSOURCE_URL', 'https://testsecureacceptance.cybersource.com/pay') }}" method="POST" id="payment">
    @csrf
    @method('POST')
    <?php
        foreach($params as $name => $value) {
            echo "<input type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }
        echo  "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . sign($params) . "\"/>\n";
       ?>
    <div class="flex mt-8">
        <button class="px-6 py-4  focus:ring-4 focus:ring-blue-300 bg-white rounded font-bold mt-3 mr-4 text-custom-gray border border-custom-gray" id="btn">Cancel</button>
         <button class="px-6 py-4 focus:ring-4 focus:ring-blue-300  bg-green-500 text-white rounded font-bold mt-3">Proceed to pay</button>
    </div>
    </form>
    </div>
</main>
<div id="modal" class="fixed inset-0 hidden items-center justify-center z-50">
  <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
  <div class="relative bg-white p-6 rounded-lg shadow-lg w-full max-w-md m-4">
    <div class="text-center">
        <div class="w-full flex justify-center items-center pb-4">
            <div class="loader h-6 w-6"></div>
    </div>
      <h2 class="text-lg font-semibold text-gray-800 mb-4">Loading...</h2>
      <p class="text-base text-gray-700">The payment form is loading</p>
    </div>
  </div>
</div>
<script>
    const textarea = document.getElementById('autoresizing-textarea');
    const form = document.getElementById('payment');
    const modal = document.getElementById('modal');

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    form.addEventListener('submit', (event) => {
        showModal();
    });
</script>
@endsection