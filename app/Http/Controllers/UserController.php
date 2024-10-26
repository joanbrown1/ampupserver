<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function allUsers(Request $request)
    {
        $users = User::all();

        return response($users, 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return response(['message' => 'Please register'], 400);
        }

        if (!password_verify($request->password, $user->password)) {
            return response(['message' => 'Incorrect Credentials'], 400);
        }

        // Optionally, generate and return a JWT token here

        return response($user, 200);
    }

    public function register(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // Check if the email already exists
    $existingUser = User::where('email', $request->input('email'))->first();

    if ($existingUser) {
        return response()->json(['message' => 'Email already exists. Please use a different email.'], 409);
    }

    // Proceed with registration if the user doesn't exist
    try {
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phonenumber' => $request->input('phonenumber'),
            'meternumber' => $request->input('meternumber'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();

        return response()->json(['message' => 'Thank you for registering. Please login to continue!'], 200);

    } catch (\Exception $e) {
        Log::error('Error registering user: ' . $e->getMessage());
        return response()->json(['message' => 'An error occurred during registration. Please try again later.'], 500);
    }
}


    public function UsersByEmail(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            return response($user, 200);
        } else {
            return response(['message' => 'User does not exist!'], 400);
        }
    }

    public function update(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        $data = $request->only(['name', 'email', 'phonenumber', 'meternumber', 'password']);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update(array_filter($data));

        return response($user, 200);
    }

    public function deleteByEmail($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response(['message' => 'User deleted successfully'], 200);
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $email = $request->input('email');

        $mailData = [
            'subject' => 'Password Reset Request',
            'body' => '
            Dear User, 
            <br/>We received a request to reset your password for your Power Kiosk account. You can reset your password by clicking the link below: 
            <br/><a href="https://powerkiosk.ng/changepassword">https://powerkiosk.ng/changepassword</a>
            <br/>If you did not request a password reset, please ignore this email. This link will expire in 24 hours for security reasons.
            <br/>If you have any questions or need further assistance, feel free to contact our support team at support@powerkiosk.ng.
            <br/><br/>Thank you, 
            <br/>Power Kiosk Team'
        ];

        try {
            Mail::send([], [], function ($message) use ($email, $mailData) {
                $message->to($email)
                        ->subject($mailData['subject'])
                        ->html($mailData['body']);  // Use the 'html' method to set the HTML content
                $message->from('no-reply@powerkiosk.ng', 'Power Kiosk Team');
            });

            return response()->json(['message' => 'Email sent'], 200);

        } catch (Exception $e) {
            // Log the error message for debugging
            Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json(['message' => 'Email sending failed'], 500);
        }
    }
}
