<?php

namespace App\Http\Controllers;

use App\Models\Failed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;

class FailedController extends Controller
{
    /**
     * Create a new Failed and send a confirmation email.
     */

    public function create(Request $request)
    {
        try {
            
            $Failed = Failed::create([
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'meternumber' => $request->input('meternumber'),
                'amount' => $request->input('amount'),
                'units' => $request->input('units'),
                'charge' => $request->input('charge'),
                'token' => $request->input('token'),
                'location' => $request->input('location'),
                'vtid' => $request->input('vtid'),
                'ppid' => $request->input('ppid'),
                'cost' => $request->input('cost'),
                'tpl' => $request->input('tpl'),
                'commision' => $request->input('commision'),
                'discountpercent' => $request->input('discountpercent'),
                'discountamount' => $request->input('discountamount'),
                'tpm' => $request->input('tpm'),
            ]);

            $Failed->save();

            // Send a confirmation email
            $email = $request->email;
            $token = $request->token;
            $amount = $request->amount;
            $charge = $request->charge;
            $units = $request->units;

            $mailData = [
                'email' => $email,
                'token' => $token,
                'amount' => $amount,
                'charge' => $charge,
                'units' => $units,
            ];

            Mail::send([], [], function ($message) use ($mailData) {
                $message->from('no-reply@powerkiosk.ng', 'PowerKiosk');
                $message->to($mailData['email']);
                $message->subject('Your Electricity Recharge Token');
                
                // HTML content for the email
                $htmlContent = '<p>Hello,<br/><br/>
                    We regret to inform you that there is currently an issue with purchasing your token. 
                    Our team is actively working to resolve the matter.<br/><br/>
                    Should the delay persist, please feel free to contact us through these channels for further assistance.
                    . Phone: <a href="tel:07073305599">0707 330 5599</a><br/>
                    . Whatsapp: 0707 330 5599<br/>
                    . In-app chat<br/>
                    . Email: <a href="mailto:support@powerkiosk.ng">support@powerkiosk.ng</a><br/><br/><br/>
                    Best regards,<br/>
                    <i>Customer care<i/>
                    <a href="https://powerkiosk.ng">https://powerkiosk.ng<a/>
                    </p>';
            
                // Attach the HTML part
                $message->html($htmlContent);
            });

            return response()->json(['message' => 'Failed created and email sent'], 200);
        } catch (\Exception $e) {
            Log::error('Error creating Failed: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all Faileds.
     */
    public function getAll()
    {
        try {
            $Faileds = Failed::orderBy('created_at', 'asc')->get();

            return response()->json($Faileds, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving Faileds: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for Faileds by email.
     */
    public function searchByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $Faileds = Failed::where('email', 'like', '%' . $request->email . '%')->orderBy('created_at', 'asc')->get();

            if ($Faileds->isEmpty()) {
                return response()->json(['message' => 'No Faileds found for the provided email'], 404);
            }

            return response()->json($Faileds, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for Faileds: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Search for Faileds by PPID.
     */
    public function searchByPpid(Request $request)
    {
        $request->validate([
            'ppid' => 'required|string',
        ]);

        try {
            $Faileds = Failed::where('ppid', 'like', '%' . $request->ppid . '%')->orderBy('created_at', 'asc')->get();

            if ($Faileds->isEmpty()) {
                return response()->json(['message' => 'No Faileds found for the provided PPID'], 404);
            }

            return response()->json($Faileds, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for Faileds: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Search for Faileds by date.
     */
    public function searchByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        try {
            $searchDate = new \DateTime($request->date);

            // Adjust start and end time for the search date
            $startDate = $searchDate->setTime(0, 0, 0);
            $endDate = $searchDate->setTime(23, 59, 59);

            $Faileds = Failed::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'asc')->get();

            if ($Faileds->isEmpty()) {
                return response()->json(['message' => 'No Faileds found for the provided date'], 404);
            }

            return response()->json($Faileds, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for Faileds: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
