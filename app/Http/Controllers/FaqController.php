<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    /**
     * Get all charges.
     */
    public function allFaqs(Request $request)
    {
        $faqs = Faq::all();
        return response($faqs, 200);
    }

    /**
     * Create a new charge.
     */
    public function create(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string'
        ]);

        try {
            $faq = new Faq([
                'question' => $request->input('question'),
                'answer' => $request->input('answer'),
            ]);
            $faq->save();

            return response($faq, 200);
        } catch (\Exception $e) {
            Log::error('Error creating charge: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
