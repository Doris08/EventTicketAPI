<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Events\EventController;
use App\Http\Controllers\Api\TicketTypes\TicketTypeController;
use App\Http\Controllers\Api\Orders\OrderController;

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

//Routes for management of events
Route::post('/events/store', [EventController::class, 'store']);
Route::get('/events/index', [EventController::class, 'index']);
Route::get('/events/show/{id}', [EventController::class, 'show']);
Route::patch('/events/update/{id}', [EventController::class, 'update']);
Route::delete('/events/delete/{id}', [EventController::class, 'destroy']);

//Routes for management of ticket types
Route::post('/ticket_types/store', [TicketTypeController::class, 'store']);
Route::get('/ticket_types/index', [TicketTypeController::class, 'index']);
Route::get('/ticket_types/show/{id}', [TicketTypeController::class, 'show']);
Route::patch('/ticket_types/update/{id}', [TicketTypeController::class, 'update']);
Route::delete('/ticket_types/delete/{id}', [TicketTypeController::class, 'destroy']);

//Routes for management of orders
Route::post('/orders/store', [OrderController::class, 'store']);
Route::get('/orders/index', [OrderController::class, 'index']);
Route::get('/orders/show/{id}', [OrderController::class, 'show']);
