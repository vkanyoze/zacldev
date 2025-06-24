<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Webhook;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CyberSourcePaymentService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = Card::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(4);
        $title  = 'Cards';
        return view('cards.indexes', compact('cards', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title  = 'Add new card';
        $countries = COUNTRIES;
        return view('cards.posts', compact('title', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'card_number' => 'required|digits:16',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => ['required', Rule::in(array_keys(COUNTRIES))],
            'postal_code' => 'sometimes',
            'email_address' => 'required|email',
            'phone_number' => 'required',
            'name' => 'required',
            'surname' => 'required',
        ]);

        if ($validator->fails()) {    
                return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        Card::create($data);

        return redirect()->route('cards.index')->with('success', 'Card created successfully.');
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
        $cardId = $request->session()->get('card_id');
        $card = Card::find($cardId);
        $card['full_name'] = $card->name . ' '. $card->surname;
        $fullName = $card->name . ' '. $card->surname;
        $address = $card->address;
        $city = $card->city;
        $postalCode = $card->postal_code;
        $country = $card->country;
        $email = $card->email_address;
        $phonenumber = $card->phone_number;
        $cardNumber = $card->card_number;
        $expiryDate = $card->expiry_date;
        $cvv = $request->session()->get('cvv');
        $paymentService = new CyberSourcePaymentService($fullName, $address, $city, $postalCode, $country, $email, $phonenumber,$cardNumber,$cvv, $expiryDate, $amountSpend);
        $results = $paymentService->createPayment();

        if ($results['status'] === 'ERROR'){
           return redirect()->route('payments.index')->with("error", $results['message']);
        }

        Payment::create([
            'full_name' => $fullName,
            'address' => $address,
            'city' => $address,
            'invoice_reference' => $formData['invoice_reference'],
            'description' => ' ',
            'payment' => ' ',
            'amount_spend' => $amountSpend,
            'service_type' => $formData['service_type'],
            'transaction_reference' => $results['processorInformation']['transactionId'],
            'reconciliaton_reference' => $results['reconciliationId'],
            'status' => $results['status'],
            'user_id' => auth()->user()->id,
            'card_id' => $card->id,
        ]);

        return redirect()->route('payments.index')->with("success", 'Payment authorised');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $card = Card::where('user_id', Auth::id())->where('id', $id)->first();
        $title = 'edit Card';
        $countries = COUNTRIES;
        return view('cards.edits', compact('card', 'title', 'countries'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator =  Validator::make($request->all(),[
            'card_number' => 'required|digits:16',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => ['required', Rule::in(array_keys(COUNTRIES))],
            'postal_code' => 'sometimes',
            'email_address' => 'required|email',
            'phone_number' => 'required',
            'name' => 'required',
            'surname' => 'required',
        ]);

        if ($validator->fails()) {    
                return back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $card = Card::where('user_id', Auth::id())->where('id', $id)->first();
        $card->update($request->all());
        return redirect()->route('cards.index')->with('success', 'Card updated successfully.');
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
        $invoice = $request->session()->get('step_one');
        $cardId = $request->session()->get('card_id');
        $card = Card::find($cardId);
        $card['full_name'] = $card->name . ' '. $card->surname;
        $title = 'select payment';
        return view('cards.cvv', compact('title',  'invoice', 'card'));
    }

    /**
     * Create new payment to 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request, $id)
    {
        $title = 'Add new payment';
        $card = Card::where('user_id', Auth::id())->where('id', $id)->first();
        $request->session()->put('card_id', $card->id);
        return view('cards.payments', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
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
        $cardId = $request->session()->get('card_id');
        $card = Card::find($cardId);
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
            "amount" => $request->get('amount_spend'),
            "card_number" => $card->card_number,
            "currency" => $request->get('currency'),
            "submit" => "Submit",
            'bill_to_email' => $card->email_address,
            'bill_to_forename' => $card->name,
            'bill_to_surname' => $card->surname,
            'bill_to_address_city' => $card->city,
            'bill_to_phone' => $card->phone_number,
            'bill_to_address_country' => $card->country,
            'bill_to_address_line1' => $card->address,
            'bill_to_address_postal_code' => trim($card->postal_code),
            'bill_to_address_state' => trim($card->state),
        ];

        $params['signed_field_names'] = trim(implode(',',array_keys($params)));
        $title = 'confirm payment';
        $invoice = $request->all();
        $invoice['service_type'] = $serviceTypesString;

        $webhook = [
            'reference_id' => $referenceNumber,
            'transaction_id' => $transactionUUD,
            'user_id' => Auth::id(),
            'save_card' => false,
            'service_type' => $serviceTypesString,
            'invoice_reference' => $invoice['invoice_reference'],
            'card_id' => ($card === null) ? null : $card->id,
            'currency' => $params['currency'],
        ];

        Webhook::create($webhook);
        $card = $card->toArray();
        $card['full_name'] = $card['name'] . ' '. $card['surname'];

        return view('cards.checkouts', compact('title', 'invoice', 'card', 'params'));
    }

    private function getReferenceNumber(int $length){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return Str::random($length, $characters);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::where('user_id', Auth::id())->where('id', $id)->first();
        $card->delete();
        return redirect()->route('cards.index')->with('success', 'Card deleted successfully.');
    }

    public function searchCards(Request $request)
    {
        $searchQuery = $request->input('query');
        $cards = Card::query()
            ->where('user_id', Auth::user()->id)
             ->where(function ($query) use ($searchQuery) {
                $query->where('card_number', 'like', "%{$searchQuery}%")
                ->orWhere('name', 'like', "%{$searchQuery}%")
                ->orWhere('surname', 'like', "%{$searchQuery}%")
                ->orWhere('address', 'like', "%{$searchQuery}%")
                ->orWhere('country', 'like', "%{$searchQuery}%")
                ->orWhere('city', 'like', "%{$searchQuery}%")
                ->orWhere('state', 'like', "%{$searchQuery}%")
                ->orWhere('card_number', 'like', "%{$searchQuery}%")
                ->orWhere('email_address', 'like', "%{$searchQuery}%")
                ->orWhere('phone_number', 'like', "%{$searchQuery}%")
                ->orWhere('postal_code', 'like', "%{$searchQuery}%");
            })
            ->paginate(4);
        
        $title = 'Search';
        return view('cards.index', compact('cards', 'title'));
    }
}
