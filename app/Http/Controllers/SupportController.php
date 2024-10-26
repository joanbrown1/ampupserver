<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SupportController extends Controller
{
    public function sendEmail(Request $request)
{
    $email = $request->input('email');
    $subject = $request->input('subject');
    $messageContent = $request->input('message');

    // Mail data
    $mailData = [
        'email' => $email,
        'subject' => $subject,
        'messageContent' => $messageContent
    ];

    try {
        Mail::send([], [], function ($message) use ($mailData) {
            $message->from($mailData['email'])
                    ->to('no-reply@powerkiosk.ng')
                    ->subject($mailData['subject'])
                    ->setBody($mailData['messageContent'], 'text/plain'); // Use the 'setBody' for plain text
        });

        return response()->json(['message' => 'Email sent'], 200);

    } catch (Exception $e) {
        // Log the error message for debugging
        Log::error('Email sending failed: ' . $e->getMessage());

        return response()->json(['message' => 'Email sending failed'], 500);
    }
}
}
