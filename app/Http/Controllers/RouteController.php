<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class RouteController extends Controller
{
    public function all()
    {
        $routes = Route::with(['user', 'category'])->get();

        $transformedRoutes = $routes->map(function ($route) {
            return [
                'id' => $route->id,
                'title' => $route->title,
                'user' => [
                    'id' => $route->user->id,
                    'name' => $route->user->name,
                ],
                'category' => [
                    'id' => $route->category->id,
                    'name' => $route->category->name,
                ],
                'destinations' => $route->destinations->map(function ($destination) {
                    return [
                        'id' => $destination->id,
                        'name' => $destination->name,
                    ];
                }),
                'image' => $route->image,
                'period' => $route->period,
                'created_at' => $route->created_at,
                'updated_at' => $route->updated_at,
            ];
        });

        return response()->json(['message' => 'All Routes', 'routes' => $transformedRoutes], 200);
    }
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'image' => 'required',
            'period' => 'required',
        ]);

        $route = new Route();
        $route->title = $request->title;
        $user = JWTAuth::parseToken()->authenticate();
        $route->user_id = $user->id;
        $route->category_id = $request->category_id;
        $route->image = $request->image;
        $route->period = $request->period;
        $route->save();

        if ($request->has('destinations') && is_array($request->destinations)) {
            foreach ($request->destinations as $destinationId) {
                $destination = Destination::find($destinationId);
                if ($destination) {
                    $route->destinations()->attach($destinationId);
                }
            }
        }

        $transformedRoute = [
            'id' => $route->id,
            'title' => $route->title,
            'user' => [
                'id' => $route->user->id,
                'name' => $route->user->name,
            ],
            'category' => [
                'id' => $route->category->id,
                'name' => $route->category->name,
            ],
            'destinations' => $route->destinations->map(function ($destination) {
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                ];
            }),
            'image' => $route->image,
            'period' => $route->period,
            'created_at' => $route->created_at,
            'updated_at' => $route->updated_at,
        ];

        return response()->json(['message' => 'Route created successfully', 'route' => $transformedRoute], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'image' => 'required',
            'period' => 'required',
        ]);

        $route = Route::find($id);
        if (!$route) {
            return response()->json(['message' => 'Route not found'], 404);
        }

        $route->title = $request->title;
        $route->category_id = $request->category_id;
        $route->image = $request->image;
        $route->period = $request->period;
        $route->save();

        if ($request->has('destinations') && is_array($request->destinations)) {
            $route->destinations()->sync($request->destinations);
        }

        $transformedRoute = [
            'id' => $route->id,
            'title' => $route->title,
            'category' => [
                'id' => $route->category->id,
                'name' => $route->category->name,
            ],
            'destinations' => $route->destinations->map(function ($destination) {
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                ];
            }),
            'image' => $route->image,
            'period' => $route->period,
            'created_at' => $route->created_at,
            'updated_at' => $route->updated_at,
        ];

        return response()->json(['message' => 'Route updated successfully', 'route' => $transformedRoute]);
    }

    public function delete($id)
    {
        $route = Route::findOrFail($id);
        $route->delete();
        return response()->json(['message' => 'Route deleted successfully'], 201);
    }

    public function search(Request $request)
    {
        $category = $request->input('category');
        $period = $request->input('period');

        $query = Route::query();

        if ($category) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            });
        }

        if ($period) {
            $query->where('period', $period);
        }

        $routes = $query->get();

        return response()->json(['routes' => $routes]);
    }

    public function addRouteToList($routeId)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $route = Route::find($routeId);
        if (!$route) {
            return response()->json(['message' => 'Route dont found'], 404);
        }
        $user->routes()->attach($routeId);
        return response()->json(['message' => 'Route added successfully']);
    }

    public function myList()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $routes = $user->routes()->get();

        if ($routes->isEmpty()) {
            return response()->json(['message' => 'No routes found in your list'], 404);
        }

        return response()->json(['routes' => $routes]);
    }
}