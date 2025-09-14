<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Payment;
use App\Models\Webhook;
use App\Services\CyberSourcePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use TNkemdilim\MoneyToWords\Converter;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(4);
        $title = 'Payments';
        return view('payments.indexes', compact('payments', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add new payment';
        return view('payments.posts', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $request->validate([
            'invoice_reference' => 'required',
            'amount_spend' => 'required',
            'currency' => ['required', Rule::in(['USD', 'ZAR', 'ZMW'])],
            'service_type' => Rule::requiredIf(function () use ($request) {
                    return empty($request->service_type_1) && empty($request->service_type_2);
            }),
        ]);

        $serviceType = $request->input('service_type');
        $otherFields = array_filter($request->only(['service_type_1', 'service_type_2']));
        $serviceTypes = array_merge([$serviceType], $otherFields);
        $serviceTypes = array_filter($serviceTypes);
        $serviceTypesString = implode(', ', $serviceTypes);
        $stepOne = $request->all();
        $stepOne['service_type'] = $serviceTypesString;
        $request->session()->put('step_one', $stepOne);

        $title = 'select payment';
        $countries = config('countries');
        return view('payments.cards', compact('title', 'stepOne', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        $formData = $request->session()->get('step_one');
        $amountSpend = $formData['amount_spend'];
        $formData = $request->session()->get('step_two');
        $fullName = $formData['full_name'];
        $address = $formData['address'];
        $city = $formData['city'];
        $postalCode = $formData['postal_code'];
        $country = $formData['country'];
        $email = $formData['email_address'];
        $phonenumber = $formData['phone_number'];
        $cardNumber = $formData['card_number'];
        $expiryDate = $formData['expiry_date'];
        $cvv = $formData['cvv'];
        $paymentService = new CyberSourcePaymentService($fullName, $address, $city, $postalCode, $country, $email, $phonenumber,$cardNumber,$cvv, $expiryDate, $amountSpend);
        $results = $paymentService->createPayment();

        if ($results['status'] === 'ERROR'){
           return redirect()->route('payments.index')->with("error", $results['message']);
        }

        // Create card record
        $sam = $request->session()->get('step_two');
        $sam['name'] = explode(' ',$sam['full_name'])[0];
        $sam['surname'] = explode(' ',$sam['full_name'])[1];
        $sam['state'] = ' ';
        $sam['user_id'] = auth()->user()->id;

        $card = Card::create($sam);
        
        // Get step one data for payment
        $stepOneData = $request->session()->get('step_one');
        
        // Prepare payment data
        $paymentData = [
            'invoice_reference' => $stepOneData['invoice_reference'],
            'description' => $stepOneData['description'] ?? 'Payment for ' . $stepOneData['service_type'],
            'service_type' => $stepOneData['service_type'],
            'amount_spend' => $stepOneData['amount_spend'],
            'payment_method' => 'credit_card',
            'transaction_reference' => $results['processorInformation']['transactionId'] ?? 'TXN_' . uniqid(),
            'reconciliaton_reference' => $results['reconciliationId'] ?? 'REC_' . uniqid(),
            'status' => $results['status'],
            'user_id' => auth()->user()->id,
            'card_id' => $card->id,
            'payment' => 'completed',
            'currency' => $stepOneData['currency'] ?? 'USD',
            'name' => $sam['name'],
            'surname' => $sam['surname']
        ];

        // Create payment record
        $payment = Payment::create($paymentData);

        // Clear session data
        $request->session()->forget(['step_one', 'step_two']);

        return redirect()->route('payments.index')->with("success", 'Payment processed successfully! Transaction ID: ' . $paymentData['transaction_reference']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
    {
        $payment = Payment::find($id);
        $converter = new Converter("dollars", "cents");
        $payment['words'] = $converter->convert($payment->amount_spend);
        $card = Card::find($payment->card_id);
        $data = compact('payment', 'card');

        $pdf = Pdf::loadView('payments.invoice',$data)->setPaper('a4', 'landscape');

        return $pdf->download(sprintf('%s.pdf', $payment->id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $title = 'confirm payment';
        $validator =  Validator::make($request->all(),[
            'card_number' => 'required|digits:16',
            'address' => 'required',
            'city' => 'required',
            'country' => ['required', Rule::in(array_keys(config('countries')))],
            'postal_code' => 'sometimes',
            'email_address' => 'required|email',
            'phone_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'state' => Rule::requiredIf(function () use ($request) {
                return $request->input('country') === 'US' || $request->input('country') === 'GB';
            }),
        ]);

        if ($validator->fails()) {    
                return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $invoice = $request->session()->get('step_one');
        $referenceNumber = $this->getReferenceNumber(20);
        $transactionUUD = uniqid();

        $params = [
            "access_key" => env('ACCESS_KEY'),
            "profile_id" => env('PROFILE_ID'),
            'transaction_uuid' =>  $transactionUUD,
            "signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_email,bill_to_forename",
            "unsigned_field_names" => null,
            'signed_date_time' => gmdate("Y-m-d\TH:i:s\Z"),
            "locale" => "en",
            "transaction_type" => "sale",
            "reference_number" => $referenceNumber,
            "amount" => $invoice['amount_spend'],
            "card_number" => $request->get('card_number'),
            "currency" => $invoice['currency'],
            "submit" => "Submit",
            'bill_to_email' => $request->get('email_address'),
            'bill_to_forename' => $request->get('first_name'),
            'bill_to_surname' => $request->get('last_name'),
            'bill_to_phone' => $request->get('phone_number'),
            'bill_to_address_city' => $request->get('city'),
            'bill_to_address_country' => trim($request->get('country')),
            'bill_to_address_line1' => trim($request->get('address')),
            'bill_to_address_postal_code' => trim($request->get('postal_code')),
            'bill_to_address_state' => trim($request->get('state')),
        ];

        $params['signed_field_names'] = trim(implode(',',array_keys($params)));
        $saveCard = $request->get('save') === '1' ? true: false;
        $card = null;
        if ($saveCard){
             $data = $request->all();
             $data['name'] = $request->get('first_name');
             $data['surname'] = $request->get('last_name');
             $data['user_id'] = Auth::id();
             $card = Card::create($data);
        }

        $serviceType = $invoice['service_type'];
        $otherFields = array_filter([$invoice['service_type_1'], $invoice['service_type_2']]);
        $serviceTypes = array_merge([$serviceType], $otherFields);
        $serviceTypes = array_filter($serviceTypes);
        $serviceTypesString = implode(', ', $serviceTypes);

        $webhook = [
            'reference_id' => $referenceNumber,
            'transaction_id' => $transactionUUD,
            'save_card' => $saveCard,
            'user_id' => Auth::id(),
            'service_type' => $serviceTypesString,
            'invoice_reference' => $invoice['invoice_reference'],
            'card_id' => ($card === null) ? null : $card->id,
            'currency' => $params['currency'],
        ];

        $card = ($card === null) ? $request->all(): $card->toArray();
        $card['full_name'] = $request->get('first_name') . ' '. $request->get('last_name');

        $webhook = Webhook::create($webhook);
        return view('payments.checkouts', compact('title', 'invoice', 'card', 'params'));
    }

    private function getReferenceNumber(int $length){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return Str::random($length, $characters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();
        $request->validate([
            'invoice_reference' => 'required',
            'card_id' => 'required',
            'description' => 'required',
            'service_type' => 'required',
            'amount_spend' => 'required|numeric',
            'payment_method' => 'required',
            'transaction_reference' => 'required',
        ]);


        $data['user_id'] = Auth::user()->id;

        $payment = Payment::create($data);
        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::where('user_id', Auth::id())->where('id', $id)->first();
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function searchCards(Request $request)
    {
        $title = 'search';
        $searchQuery = $request->input('query');
        $payments = Payment::query()
             ->where('user_id', Auth::user()->id)
             ->where(function ($query) use ($searchQuery) {
                $query->where('invoice_reference', 'like', "%{$searchQuery}%")
                ->orWhere('description', 'like', "%{$searchQuery}%")
                ->orWhere('service_type', 'like', "%{$searchQuery}%")
                ->orWhere('amount_spend', '=', $searchQuery)
                ->orWhere('status', 'like', "%{$searchQuery}%")
                ->orWhere('transaction_reference', 'like', "%{$searchQuery}%");
            })->paginate(10);
        
        return view('payments.index', compact('payments', 'title'));
    }

    /**
     * Export payments data as CSV
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=payments_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() {
            $user = Auth::user();
            $file = fopen('php://output', 'w');
            
            // Add User Information as headers
            fputcsv($file, ['User Name', $user->name ?? 'N/A']);
            fputcsv($file, ['User Email', $user->email ?? 'N/A']);
            fputcsv($file, ['User ID', $user->id ?? 'N/A']);
            fputcsv($file, ['Report Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty line for separation
            
            // Add CSV headers for payments
            fputcsv($file, [
                'Date', 
                'Invoice Number',
                'Transaction ID',
                'Description', 
                'Payment Method', 
                'Amount', 
                'Currency',
                'Status',
                'Created At',
                'Updated At'
            ]);

            $payments = Payment::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->invoice_number ?? 'N/A',
                    $payment->transaction_id ?? 'N/A',
                    $payment->description ?? 'N/A',
                    $payment->payment_method ?? 'N/A',
                    $payment->amount_spend ? number_format($payment->amount_spend, 2) : '0.00',
                    $payment->currency ?? 'USD',
                    $payment->status ?? 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            // Add Summary
            fputcsv($file, []); // Empty line for separation
            fputcsv($file, ['Total Payments', $payments->count()]);
            fputcsv($file, ['Total Amount', number_format($payments->sum('amount_spend'), 2)]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
