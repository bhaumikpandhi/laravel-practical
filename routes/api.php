<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LoginController;

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

Route::get('/user', function () {
    return response()->json(['a' => 'b']);
})->middleware('auth:api');

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);


Route::apiResource('tasks', TaskController::class)->middleware('auth:api');
Route::apiResource('notes', NoteController::class)->middleware('auth:api');
