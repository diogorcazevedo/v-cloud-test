<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('login',    [AuthController::class, 'login']);
});
Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::post('logout',   [AuthController::class, 'logout']);
    Route::post('refresh',  [AuthController::class, 'refresh']);
    Route::post('me',       [AuthController::class, 'me']);

});


Route::middleware('auth:api')->prefix('deal')->group(function () {

    Route::get('getAll',                                [DealController::class, 'getAll']);
    Route::get('getAllByActiveStatus/{active}',         [DealController::class, 'getAllByActiveStatus']);
    Route::get('getByStatus/{status}',                  [DealController::class, 'getByStatus']);
    Route::get('getFromUserByStatus/{id}/{status}',     [DealController::class, 'getFromUserByStatus']);
    Route::get('getAllFromUser/{id}',                   [DealController::class, 'getAllFromUser']);
    Route::get('getById/{id}',                          [DealController::class, 'getById']);
    Route::any('store/{user}',                          [DealController::class, 'store']);
    Route::any('update/{id}',                           [DealController::class, 'update']);
    Route::any('updateUrgency/{id}',                    [DealController::class, 'updateUrgency']);
    Route::any('updateLocation/{id}',                   [DealController::class, 'updateLocation']);
    Route::any('imageStore/{id}',                       [DealController::class, 'imageStore']);
    Route::any('active/{id}',                           [DealController::class, 'active']);
});




Route::middleware('auth:api')->prefix('bid')->group(function () {

    Route::get('getAllFromDeal/{id}',           [BidController::class, 'getAllFromDeal']);
    Route::get('getAllFromUser/{id}',           [BidController::class, 'getAllFromUser']);
    Route::get('getById/{id}',                  [BidController::class, 'getById']);
    Route::any('store/{deal_id}/{user_id}',     [BidController::class, 'store']);
    Route::any('accepted/{bid_id}',             [BidController::class, 'accepted']);
});


Route::middleware('auth:api')->prefix('message')->group(function () {

    Route::get('getAllFromUser/{id}',                           [MessageController::class, 'getAllFromUser']);
    Route::get('getAllFromDeal/{id}',                           [MessageController::class, 'getAllFromDeal']);
    Route::get('getAllFromDealAndUser/{deal}/{user}',           [MessageController::class, 'getAllFromDealAndUser']);
    Route::get('getById/{id}',                                  [MessageController::class, 'getById']);
    Route::any('store/{deal_id}/{user_id}',                     [MessageController::class, 'store']);
    Route::any('update/{id}',                                   [MessageController::class, 'update']);
});


Route::middleware('auth:api')->prefix('register')->group(function () {

    Route::get('show/{id}',                 [RegisterController::class, 'show']);
    Route::any('store',                     [RegisterController::class, 'store']);
    Route::any('update/{id}',               [RegisterController::class, 'update']);
    Route::any('updatePassword/{id}',       [RegisterController::class, 'updatePassword']);
    Route::any('updateLocation/{id}',       [RegisterController::class, 'updateLocation']);

});


Route::middleware('auth:api')->prefix('invite')->group(function () {
    Route::get('show/{id}',             [InviteController::class, 'show']);
    Route::get('getAllFromUser/{id}',   [InviteController::class, 'getAllFromUser']);
    Route::any('store/{user_id}',       [InviteController::class, 'store']);
    Route::any('update/{id}',           [InviteController::class, 'update']);


});

//
