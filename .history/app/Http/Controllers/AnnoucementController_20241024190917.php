<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnoucementController extends Controller
{
     // Fetch all Annoucements
     public function index()
     {
         return Announcement::all(); // Include logo and active fields automatically
     }
 
     // Update the active status of an Announcement
     public function update(Request $request)
    {
        // Retrieve the 'id' from the request body
        $id = $request->input('id');

        // Find the Announcement by ID
        $Announcement = Announcement::find($id);

        if ($Announcement) {
            // Update the active status
            $Announcement->active = $request->input('active');
            $Announcement->save();

            return response()->json(['message' => 'Announcement updated successfully.'], 200);
        }

        return response()->json(['message' => 'Announcement not found.'], 404);
    }

}
