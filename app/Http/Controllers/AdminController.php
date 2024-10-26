<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use Exception;

class AdminController extends Controller
{
    public function allAdmins(Request $request)
    {
        $admins = Admin::all();

        return response($admins, 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::whereEmail($request->email)->first();

        if (!$admin) {
            return response(['message' => 'Please register'], 400);
        }

        if (!password_verify($request->password, $admin->password)) {
            return response(['message' => 'Incorrect Credentials'], 400);
        }

        // Optionally, generate and return a JWT token here

        return response($admin, 200);
    }

    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email|unique:admins',
            'privilage' => 'required',
            'password' => 'required'
        ]);
    
        // Check if the email already exists
        $existingAdmin = Admin::where('email', $request->input('email'))->first();

        if ($existingAdmin) {
            return response()->json(['message' => 'Email already exists. Please use a different email.'], 409);
        }

        // Proceed with registration if the user doesn't exist
        try {
            // Create a new Admin instance
            $admin = new Admin([
                'email' => $request->input('email'),
                'privilage' => $request->input('privilage'),
                'password' => bcrypt($request->input('password')),
            ]);
    
            // Save the admin to the database
            $admin->save();
    
        } catch (Exception $e) {
            // Log the error and return a response with the error message
            Log::error('Error creating admin: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed, please try again later.'], 500);
        }
    
        // Send the welcome email using a Mailable class
        $mailData = [
            'subject' => "Congratulations, you have been given {$admin->privilage} rights",
            'body' => "
                Dear User, 
                <h1>Hello, {$admin->email}!</h1>
                <br/><p>Password: {$request->input('password')}</p>
                <br/><br/><p>Best regards,<br>PowerKiosk Team</p>"
        ];
    
        try {
            // Send the email
            Mail::send([], [], function ($message) use ($admin, $mailData) {
                $message->to($admin->email)
                        ->subject($mailData['subject'])
                        ->html($mailData['body']);  // Use the 'html' method to set the HTML content
                $message->from('no-reply@powerkiosk.ng', 'Power Kiosk Team');
            });
    
        } catch (Exception $e) {
            // Log the error if email sending fails
            Log::error('Error sending email: ' . $e->getMessage());
        }
    
        // Return a success response
        return response()->json(['message' => 'Thank you for registering. Please log in to continue!'], 200);
    }

    public function AdminsByEmail(Request $request)
    {
        $admin = Admin::where('email', $request->input('email'))->first();
        if ($admin) {
            return response($admin, 200);
        } else {
            return response(['message' => 'Admin does not exist!'], 400);
        }
    }

    public function update(Request $request)
    {
        $admin = Admin::where('email', $request->input('email'))->first();
        if (!$admin) {
            return response(['message' => 'Admin not found'], 404);
        }

        $data = $request->only(['email', 'privilage', 'password']);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $admin->update(array_filter($data));

        return response($admin, 200);
    }

    public function deleteByEmail($email)
    {
        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            return response(['message' => 'Admin not found'], 404);
        }

        $admin->delete();

        return response(['message' => 'Admin deleted successfully'], 200);
    }
}
