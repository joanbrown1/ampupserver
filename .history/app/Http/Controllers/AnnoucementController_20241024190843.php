<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnoucementController extends Controller
{
     // Fetch all Annoucements
     public function index()
     {
         return Annoucement::all(); // Include logo and active fields automatically
     }
 
     // Update the active status of an Annoucement
     public function update(Request $request)
    {
        // Retrieve the 'id' from the request body
        $id = $request->input('id');

        // Find the Annoucement by ID
        $Annoucement = Annoucement::find($id);

        if ($Annoucement) {
            // Update the active status
            $Annoucement->active = $request->input('active');
            $Annoucement->save();

            return response()->json(['message' => 'Annoucement updated successfully.'], 200);
        }

        return response()->json(['message' => 'Annoucement not found.'], 404);
    }

}
