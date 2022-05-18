<?php


use App\Http\Controllers\Api\{
    ProductController,
    LocationController,
    AgentController,
    AuthController,
    OrderController,
};

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1'], function (){

    Route::post('/register',[AuthController::class, 'register']);
    Route::post('/login',[AuthController::class, 'login']);

    Route::post('webhook', [OrderController::class, 'webhook']);

    Route::group(['middleware' => 'auth:sanctum'], function (){

        Route::post('/logout',[AuthController::class, 'logout']);

        Route::group(['prefix' => 'admin', 'middleware' => ['role:admin'] ], function (){

            Route::apiResource('products', ProductController::class );

            Route::post('agents', [AgentController::class, 'create']);
            Route::get('agents', [AgentController::class, 'index']);
        });
        
        Route::get('location', [LocationController::class, 'getUserLocation']);
        Route::post('order', [OrderController::class, 'create']);
        
    });

});

