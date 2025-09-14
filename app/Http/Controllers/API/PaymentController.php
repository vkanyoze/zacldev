<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/payments",
     *     summary="Get list of payments",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of payments",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Payment")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($payments);
    }

    /**
     * @OA\Post(
     *     path="/api/payments",
     *     summary="Create a new payment",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PaymentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'description' => 'required|string|max:255',
            'card_id' => 'required|exists:cards,id,user_id,' . Auth::id(),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = new Payment($request->all());
        $payment->user_id = Auth::id();
        $payment->status = 'pending';
        $payment->save();

        // Process payment through your payment service here

        return response()->json($payment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     summary="Get payment details",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Payment ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment details",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function show($id)
    {
        $payment = Payment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return response()->json($payment);
    }

    /**
     * @OA\Post(
     *     path="/api/payments/{id}/refund",
     *     summary="Refund a payment",
     *     tags={"Payments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Payment ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Refund processed successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Refund not possible"
     *     )
     * )
     */
    public function refund($id)
    {
        $payment = Payment::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Process refund through your payment service here
        $payment->status = 'refunded';
        $payment->save();

        return response()->json(['message' => 'Refund processed successfully']);
    }
}
