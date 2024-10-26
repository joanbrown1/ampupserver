<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChargeController extends Controller
{
    /**
     * Get all charges.
     */
    public function allCharges(Request $request)
    {
        $charges = Charge::orderBy('created_at', 'asc')->get();;
        return response($charges, 200);
    }

    /**
     * Create a new charge.
     */
    public function create(Request $request)
    {
        $request->validate([
            'charge' => 'required|numeric'
        ]);

        try {
            $charge = new Charge([
                'charge' => $request->input('charge'),
            ]);
            $charge->save();

            return response($charge, 200);
        } catch (\Exception $e) {
            Log::error('Error creating charge: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
