<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class DestinationController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'list_of_places' => 'required',
            'place_of_accommodation' => 'required',
        ]);

        $destination = new Destination();
        $destination->name = $request->name;
        $destination->list_of_places = $request->list_of_places;
        $destination->place_of_accommodation = $request->place_of_accommodation;
        $user = JWTAuth::parseToken()->authenticate();
        $destination->user_id = $user->id;

        $destination->save();
        return response()->json(['message' => 'Destination created successfully', 'destination' => $destination], 201);
    }
}
