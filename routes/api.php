<?php

use App\Http\Controllers\DestinationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/routes', [RouteController::class, 'all']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/route/create', [RouteController::class, 'create']);
Route::get('/route/search', [RouteController::class, 'search']);
Route::put('/route/{id}/update', [RouteController::class, 'update']);
Route::delete('/route/{id}/delete', [RouteController::class, 'delete']);
Route::post('/route/{routeId}/add-to-list', [RouteController::class, 'addRouteToList']);
Route::post('/destination/create', [DestinationController::class, 'create']);
Route::post('/category/create', [CategoryController::class, 'create']);
Route::get('/myList', [RouteController::class, 'myList']);