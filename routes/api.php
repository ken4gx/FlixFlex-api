<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ShowController;
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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });


// User Routes
Route::post('subscribe', [AuthController::class,'subscribe']);
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});

// Movies Routes
Route::group(['middleware'=>'api','prefix'=>'movies'],function(){
    Route::get('search',[MovieController::class,'search']);
    Route::get('list',[MovieController::class,'index']);
    Route::get('topRate',[MovieController::class,'topRate']);
    Route::get('details/{id}',[MovieController::class,'detail']);
    Route::get('details/{id}/trailer',[MovieController::class,'trailer']);
    Route::get('addToFavorite/{id}',[MovieController::class,'addToFav']);
});

// Shows Routes
Route::group(['middleware'=>'api','prefix'=>'shows'],function(){
    Route::get('search',[ShowController::class,'search']);
    Route::get('list',[ShowController::class,'index']);
    Route::get('topRate',[ShowController::class,'topRate']);
    Route::get('details/{id}',[ShowController::class,'detail']);
    Route::get('details/{id}/trailer',[ShowController::class,'trailer']);
    Route::get('addToFavorite/{id}',[ShowController::class,'addToFav']);
});

// Favorite Routes
Route::group(['middleware'=>'api','prefix'=>'favorites'],function(){
    Route::get('movies',[FavoriteController::class,'movies']);
    Route::get('shows',[FavoriteController::class,'shows']);
    Route::delete('deleteMovie/{id}',[FavoriteController::class,'destroyMovie']);
    Route::delete('deleteShow/{id}',[FavoriteController::class,'destroyShow']);
});
