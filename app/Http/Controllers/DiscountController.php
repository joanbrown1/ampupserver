<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    /**
     * Create a new discount.
     */

    public function create(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'limit' => 'required'
        ]);

        // Check if the email already exists
        $existingCode = Discount::where('code', $request->input('code'))->first();

        if ($existingCode) {
            return response()->json(['message' => 'Code already exists. Please use a different code.'], 409);
        }

        // Proceed with registration if the user doesn't exist
            try {
            $discount = new Discount([
                'code' => $request->input('code'),
                'percent' => $request->input('percent'),
                'amount' => $request->input('amount'),
                'limit' => $request->input('limit'),
            ]);
            $discount->save();

            return response($discount, 200);

        } catch (\Exception $e) {
            Log::error('Error creating discount: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /*
     * Get all discounts.
     */
    public function getAll()
    {
        try {
            $discounts = Discount::orderBy('created_at', 'asc')->get();

            return response()->json($discounts, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving discounts: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search discounts by code.
     */
    public function searchByCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $code = $request->input('code');
            $discounts = Discount::where('code', 'like', '%' . $code . '%')->get();

            if ($discounts->isEmpty()) {
                return response()->json(['message' => 'No discounts found for the provided code'], 404);
            }

            return response()->json($discounts, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for discounts: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
