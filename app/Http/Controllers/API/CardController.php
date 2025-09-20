<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cards",
     *     summary="Get user's cards",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of user's cards",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Card"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $cards = Card::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cards",
     *     summary="Create a new card",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"card_number","expiry_month","expiry_year","cardholder_name"},
     *             @OA\Property(property="card_number", type="string"),
     *             @OA\Property(property="expiry_month", type="integer", minimum=1, maximum=12),
     *             @OA\Property(property="expiry_year", type="integer"),
     *             @OA\Property(property="cvv", type="string"),
     *             @OA\Property(property="cardholder_name", type="string"),
     *             @OA\Property(property="is_default", type="boolean", default=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Card created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
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
            'card_number' => 'required|string|min:13|max:19',
            'expiry_month' => 'required|integer|min:1|max:12',
            'expiry_year' => 'required|integer|min:' . date('Y'),
            'cardholder_name' => 'required|string|max:255',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If this is set as default, unset other default cards
        if ($request->is_default) {
            Card::where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $card = Card::create([
            'user_id' => Auth::id(),
            'card_number' => $request->card_number,
            'expiry_month' => $request->expiry_month,
            'expiry_year' => $request->expiry_year,
            'cvv' => $request->cvv ?? null,
            'cardholder_name' => $request->cardholder_name,
            'is_default' => $request->is_default ?? false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Card created successfully',
            'data' => $card
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/cards/{id}",
     *     summary="Get card details",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Card ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card details",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card not found"
     *     )
     * )
     */
    public function show($id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $card
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/cards/{id}",
     *     summary="Update card",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Card ID"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="expiry_month", type="integer", minimum=1, maximum=12),
     *             @OA\Property(property="expiry_year", type="integer"),
     *             @OA\Property(property="cvv", type="string"),
     *             @OA\Property(property="cardholder_name", type="string"),
     *             @OA\Property(property="is_default", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'expiry_month' => 'integer|min:1|max:12',
            'expiry_year' => 'integer|min:' . date('Y'),
            'cardholder_name' => 'string|max:255',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // If this is set as default, unset other default cards
        if ($request->has('is_default') && $request->is_default) {
            Card::where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $card->update($request->only([
            'expiry_month',
            'expiry_year',
            'cvv',
            'cardholder_name',
            'is_default'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Card updated successfully',
            'data' => $card
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/cards/{id}",
     *     summary="Delete card",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Card ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found'
            ], 404);
        }

        $card->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card deleted successfully'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/cards/search",
     *     summary="Search cards",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Search query"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Card"))
     *         )
     *     )
     * )
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $cards = Card::where('user_id', Auth::id())
            ->where(function($q) use ($query) {
                $q->where('cardholder_name', 'like', "%{$query}%")
                  ->orWhere('card_number', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/cards/{id}/set-default",
     *     summary="Set card as default",
     *     tags={"Cards"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Card ID"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Card set as default successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card not found"
     *     )
     * )
     */
    public function setDefault($id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found'
            ], 404);
        }

        // Unset other default cards
        Card::where('user_id', Auth::id())
            ->where('id', '!=', $id)
            ->update(['is_default' => false]);

        // Set this card as default
        $card->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Card set as default successfully',
            'data' => $card
        ]);
    }
}
