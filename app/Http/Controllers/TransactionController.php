<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;

class TransactionController extends Controller
{
    /**
     * Create a new transaction and send a confirmation email.
     */

    public function create(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        //     'phone' => 'required|string',
        //     'meternumber' => 'required|string',
        //     'amount' => 'required|numeric',
        //     'units' => 'required|string',
        //     'charge' => 'required|numeric',
        //     'token' => 'required|string',
        //     'location' => 'required|string',
        //     'vtid' => 'required|string',
        //     'ppid' => 'required|string',
        //     'cost' => 'required|numeric',
        //     'tpl' => 'required|numeric',
        //     'commision' => 'required|numeric',
        //     'discountpercent' => 'required|numeric',
        //     'discountamount' => 'required|numeric',
        //     'tpm' => 'required|numeric',
        // ]);

        try {
            
            $transaction = Transaction::create([
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

            $transaction->save();

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
                    Thank you for trusting us in providing you with a seamless purchase process for your Electricity Token.<br/><br/>
                    Details for your purchase are as below:<br/>
                    <b>Token: ' . $mailData['token'] . '<b/><br/>
                    <b>Amount: ' . $mailData['amount'] . '<b/><br/>
                    <b>Charge: ' . $mailData['charge'] . '<b/><br/>
                    <b>KWH: ' . $mailData['units'] . '<b/><br/><br/>
                    If you have challenges with this purchase, you can reach us on the following channels:<br/><br/>
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

            return response()->json(['message' => 'Transaction created and email sent'], 200);
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all transactions.
     */
    public function getAll()
    {
        try {
            $transactions = Transaction::orderBy('created_at', 'asc')->get();

            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving transactions: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for transactions by email.
     */
    public function searchByEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $transactions = Transaction::where('email', 'like', '%' . $request->email . '%')->orderBy('created_at', 'asc')->get();

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'No transactions found for the provided email'], 404);
            }

            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for transactions: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Search for transactions by PPID.
     */
    public function searchByPpid(Request $request)
    {
        $request->validate([
            'ppid' => 'required|string',
        ]);

        try {
            $transactions = Transaction::where('ppid', 'like', '%' . $request->ppid . '%')->orderBy('created_at', 'asc')->get();

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'No transactions found for the provided PPID'], 404);
            }

            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for transactions: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Search for transactions by date.
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

            $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'asc')->get();

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'No transactions found for the provided date'], 404);
            }

            return response()->json($transactions, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for transactions: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
