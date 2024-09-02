<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('todos', [TodoController::class, 'store']);
Route::get('todos/fetch/{status}', [TodoController::class, 'fetch']);
Route::put('todos/statusUpdate/{id}', [TodoController::class, 'statusUpdate']);
Route::delete('todos/delete/{id}', [TodoController::class, 'delete']);
