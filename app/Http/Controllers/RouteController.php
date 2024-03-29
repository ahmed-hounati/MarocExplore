<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

/**
 * @OA\Info(
 *      title="Route API",
 *      version="1.0.0",
 *      description="APIs for managing routes",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 */

/**
 * @OA\Get(
 *      path="/api/routes",
 *      operationId="getAllRoutes",
 *      tags={"Routes"},
 *      summary="Get all routes",
 *      description="Returns all routes with their details",
 *      @OA\Response(
 *          response=200,
 *          description="All routes retrieved successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="All Routes"),
 *              @OA\Property(property="routes", type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="title", type="string"),
 *                      @OA\Property(property="user", type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                      @OA\Property(property="category", type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                      @OA\Property(property="destinations", type="array",
 *                          @OA\Items(
 *                              type="object",
 *                              @OA\Property(property="id", type="integer"),
 *                              @OA\Property(property="name", type="string"),
 *                          ),
 *                      ),
 *                      @OA\Property(property="image", type="string"),
 *                      @OA\Property(property="period", type="string"),
 *                      @OA\Property(property="created_at", type="string"),
 *                      @OA\Property(property="updated_at", type="string"),
 *                  )
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/route/create",
 *      operationId="createRoute",
 *      tags={"Routes"},
 *      summary="Create a new route",
 *      description="Creates a new route with the provided details",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Route data",
 *          @OA\JsonContent(
 *              required={"title", "category_id", "image", "period"},
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="category_id", type="integer"),
 *              @OA\Property(property="image", type="string"),
 *              @OA\Property(property="period", type="string")
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Route created successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Route created successfully"),
 *              @OA\Property(property="route", type="object",
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="title", type="string"),
 *                  @OA\Property(property="category", type="object",
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="name", type="string"),
 *                  ),
 *                  @OA\Property(property="destinations", type="array",
 *                      @OA\Items(
 *                          type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                  ),
 *                  @OA\Property(property="image", type="string"),
 *                  @OA\Property(property="period", type="string"),
 *                  @OA\Property(property="created_at", type="string"),
 *                  @OA\Property(property="updated_at", type="string"),
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Put(
 *      path="/api/route/{id}/update",
 *      operationId="updateRoute",
 *      tags={"Routes"},
 *      summary="Update an existing route",
 *      description="Updates an existing route with the provided details",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the route to be updated",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Route data",
 *          @OA\JsonContent(
 *              required={"title", "category_id", "image", "period"},
 *              @OA\Property(property="title", type="string"),
 *              @OA\Property(property="category_id", type="integer"),
 *              @OA\Property(property="image", type="string"),
 *              @OA\Property(property="period", type="string")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Route updated successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Route updated successfully"),
 *              @OA\Property(property="route", type="object",
 *                  @OA\Property(property="id", type="integer"),
 *                  @OA\Property(property="title", type="string"),
 *                  @OA\Property(property="category", type="object",
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="name", type="string"),
 *                  ),
 *                  @OA\Property(property="destinations", type="array",
 *                      @OA\Items(
 *                          type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                  ),
 *                  @OA\Property(property="image", type="string"),
 *                  @OA\Property(property="period", type="string"),
 *                  @OA\Property(property="created_at", type="string"),
 *                  @OA\Property(property="updated_at", type="string"),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Route not found"
 *      )
 * )
 *
 * @OA\Delete(
 *      path="/api/routes/{id}/delete",
 *      operationId="deleteRoute",
 *      tags={"Routes"},
 *      summary="Delete a route",
 *      description="Deletes a route with the specified ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the route to be deleted",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Route deleted successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Route deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Route not found"
 *      )
 * )
 *
 * @OA\Get(
 *      path="/api/route/search",
 *      operationId="searchRoutes",
 *      tags={"Routes"},
 *      summary="Search routes",
 *      description="Searches routes based on category and period",
 *      @OA\Parameter(
 *          name="category",
 *          in="query",
 *          description="Category name",
 *          required=false,
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Parameter(
 *          name="period",
 *          in="query",
 *          description="Period",
 *          required=false,
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Routes retrieved successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="routes", type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="title", type="string"),
 *                      @OA\Property(property="user", type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                      @OA\Property(property="category", type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                      @OA\Property(property="destinations", type="array",
 *                          @OA\Items(
 *                              type="object",
 *                              @OA\Property(property="id", type="integer"),
 *                              @OA\Property(property="name", type="string"),
 *                          ),
 *                      ),
 *                      @OA\Property(property="image", type="string"),
 *                      @OA\Property(property="period", type="string"),
 *                      @OA\Property(property="created_at", type="string"),
 *                      @OA\Property(property="updated_at", type="string"),
 *                  )
 *              )
 *          )
 *      )
 * )
 *
 * @OA\Post(
 *      path="/api/route/{routeId}/add-to-list",
 *      operationId="addRouteToList",
 *      tags={"Routes"},
 *      summary="Add route to user's list",
 *      description="Adds a route to the authenticated user's list",
 *      @OA\Parameter(
 *          name="routeId",
 *          in="path",
 *          description="ID of the route to be added",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Route added successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="string", example="Route added successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Route not found"
 *      )
 * )
 *
 * @OA\Get(
 *      path="/api/myList",
 *      operationId="getUserRoutes",
 *      tags={"Routes"},
 *      summary="Get user's routes",
 *      description="Retrieves routes added by the authenticated user",
 *      @OA\Response(
 *          response=200,
 *          description="Routes retrieved successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="routes", type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      @OA\Property(property="id", type="integer"),
 *                      @OA\Property(property="title", type="string"),
 *                      @OA\Property(property="user", type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                      @OA\Property(property="category", type="object",
 *                          @OA\Property(property="id", type="integer"),
 *                          @OA\Property(property="name", type="string"),
 *                      ),
 *                      @OA\Property(property="destinations", type="array",
 *                          @OA\Items(
 *                              type="object",
 *                              @OA\Property(property="id", type="integer"),
 *                              @OA\Property(property="name", type="string"),
 *                          ),
 *                      ),
 *                      @OA\Property(property="image", type="string"),
 *                      @OA\Property(property="period", type="string"),
 *                      @OA\Property(property="created_at", type="string"),
 *                      @OA\Property(property="updated_at", type="string"),
 *                  )
 *              )
 *          )
 *      )
 * )
 */

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