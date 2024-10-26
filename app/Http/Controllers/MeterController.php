<?php

namespace App\Http\Controllers;

use App\Models\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeterController extends Controller
{
    /**
     * Add a new meter number.
     */
     public function create(Request $request)
     {
        $request->validate([
            'meter' => 'required|string',
            'email' => 'required|email',
        ]);

        try {
            
            $meter = Meter::create([
                'meter' => $request->input('meter'),
                'email' => $request->input('email'),
            ]);

            $meter->save();
 
            return response($meter, 200);
        } catch (\Exception $e) {
            Log::error('Error creating conversation: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get all meter numbers.
     */
    public function getAll()
    {
        try {
            $meters = Meter::orderBy('created_at', 'asc')->get();

            return response()->json($meters, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving meters: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for meters by email.
     */
    public function searchByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $meters = Meter::where('email', 'like', '%' . $request->email . '%')->get();

            if ($meters->isEmpty()) {
                return response()->json(['message' => 'No meters found for the provided email'], 404);
            }

            return response()->json($meters, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for meters: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
