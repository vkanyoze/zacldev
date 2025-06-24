<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Webhook;
use App\Models\Payment;

class WebhookController extends Controller
{
     public function handleWebhook(Request $request)
    {
        $reqReferenceNumber = $request->input('req_reference_number');
        $reqTransactionUuid = $request->input('req_transaction_uuid');

        if (!$reqReferenceNumber && !$reqTransactionUuid) {
            return response()->json(['message' => 'Invalid webhook data'], 400);
        }
        $payload = $request->all();
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "webhook_logs/webhook_{$timestamp}.json";
        Storage::disk('local')->put($filename, json_encode($payload, JSON_PRETTY_PRINT));

        $webhook = Webhook::where('transaction_id', $reqTransactionUuid)
                            ->where('reference_id', $reqReferenceNumber)
                            ->first();

        if ($webhook) {
            $payment = new Payment([
                'status' => $request->input('decision'),
                'amount_spend' => $request->input('req_amount'),
                'transaction_reference' => $reqTransactionUuid,
                'reconciliaton_reference' => $reqReferenceNumber,
                'user_id' => $webhook->user_id,
                'card_id' => $webhook->card_id,
                'service_type' => $webhook->service_type,
                'service_type' => $webhook->service_type,
                'invoice_reference' => $webhook->invoice_reference,
                'currency' => $webhook->currency,
                'name' => $request->input('req_bill_to_forename'),
                'surname' => $request->input('req_bill_to_surname'),
                'description' => $request->input('message'),
                'payment' => ' ',
            ]);
            $payment->save();

            return response()->json(['message' => 'Webhook processed and payment created'], 200);
        }



        return response()->json(['message' => 'Invalid webhook data'], 400);
    }
}
