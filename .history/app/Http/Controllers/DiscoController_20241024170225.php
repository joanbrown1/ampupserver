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
     public function update(Request $request, $id)
     {
         $Disco = Disco::find($id);
 
         if ($Disco) {
             $Disco->active = $request->active;
             $Disco->save();
 
             return response()->json(['message' => 'Disco updated successfully.'], 200);
         }
 
         return response()->json(['message' => 'Disco not found.'], 404);
     }
}
