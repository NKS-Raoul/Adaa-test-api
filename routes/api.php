<?php

use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\UserController;
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
// 
// 
//                    The 4 end points
// 
// 
Route::middleware(['auth:sanctum', 'abilities:check-status,place-orders'])->group(function () {
    Route::post('/post/add', [ContentController::class, 'createPost']);
    Route::post('/beat/add', [ContentController::class, 'createBeat']);
});
// 
// 
//                    Authentication
// 
// 
Route::post('/auth/login', [UserController::class, 'loginUser']);
Route::post('/auth/register', [UserController::class, 'createUser']);
