<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\NewPasswordController;

// Route::group(['prefix' => '/auth', ['middleware' => 'web']], function () {
//     //Route::post('/login', [LoginController::class, 'login']);
//     Route::post('/register', [LoginController::class, 'register']);
// });
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);
Route::post('/forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [NewPasswordController::class, 'reset']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->only(['uuid', 'name', 'email']);
        return response()->json([
            'user' => $user,
            'message' => 'Success',
        ], 200);
        // return response()->json($user, 200);
    });
    Route::get('/refresh', function (Request $request) {
        //$request->user()->tokens()->delete();
        print_r($request->user()->currentAccessToken());
        //$request->user()->currentAccessToken()->delete();
        return response()->json([
            'refresh_token' => true,
            'message' => 'Success',
        ], 200);
        // return response()->json($user, 200);
    });

    Route::post('/resetPassword', [LoginController::class, 'resetPassword']);
    Route::post('/logout', [LoginController::class, 'logout']);
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
