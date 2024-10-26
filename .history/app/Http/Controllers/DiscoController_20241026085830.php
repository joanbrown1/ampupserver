<?php

namespace App\Http\Controllers;
use App\Models\Disco;
use Illuminate\Http\Request;

class DiscoController extends Controller
{
     // Fetch all Discos
     public function index()
     {
         return Disco::all(); // Include logo and active fields automatically
     }
 
     // Update the active status of an Disco
     public function update(Request $request)
    {
        // Retrieve the 'id' from the request body
        $id = $request->input('id');

        // Find the Disco by ID
        $Disco = Disco::find($id);

        if ($Disco) {
            // Update the Disco fields dynamically based on the request input
            $Disco->fill($request->except('id'));
            $Disco->save();

            return response()->json(['message' => 'Disco updated successfully.'], 200);
        }

        return response()->json(['message' => 'Disco not found.'], 404);
    }

     

     public function DiscosById(Request $request)
    {
        $disco = Disco::where('id', $request->input('id'))->first();
        if ($disco) {
            return response($disco, 200);
        } else {
            return response(['message' => 'disco does not exist!'], 400);
        }
    }
}
