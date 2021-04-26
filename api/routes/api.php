<?php

use App\Http\Controllers\API\EmailController;
use App\Http\Controllers\API\RecipientController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
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
    Route::get('/user', [ UserController::class, 'show' ])->name('api.user');

    Route::get('/tokens', [ TokenController::class, 'index' ])->name('api.tokens.get');
    Route::post('/tokens', [ TokenController::class, 'create' ])->name('api.tokens.create');
    Route::delete('/tokens/{tokenID}', [ TokenController::class, 'revoke' ])->name('api.tokens.revoke');

    Route::get('/recipients', [ RecipientController::class, 'index'])->name('api.recipients.get');
    Route::get('/recipients/{recipient}', [ RecipientController::class, 'show']);

    Route::get('/emails', [ EmailController::class, 'index']);
    Route::post('/emails', [ EmailController::class, 'store']);
});
