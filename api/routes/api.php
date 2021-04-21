<?php

use App\Http\Controllers\API\EmailsController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function(){
    Route::get('/user', [ UsersController::class, 'show' ]);

    Route::get('/tokens', [ TokenController::class, 'index' ]);
    Route::post('/tokens', [ TokenController::class, 'create' ]);

    Route::get('/emails', [ EmailsController::class, 'index']);
    Route::post('/emails', [ EmailsController::class, 'store']);
});
