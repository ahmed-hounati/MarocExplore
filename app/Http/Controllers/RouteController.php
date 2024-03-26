<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'description' => 'required',
        ]);

        $route = new Route();
        $route->name = $request->name;
        $route->destination_id = $request->destination_id;
        $route->user_id = Auth::id();
        $route->category_id = $request->categor_id;
        $route->image = $request->image;
        $route->period = $request->period;

        $route->save();

        return response()->json(['message' => 'Route created successfully', 'route' => $route], 201);
    }
}
