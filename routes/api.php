<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;
use App\Models\Product;
use App\Models\Warehouse;

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

/*
Ombor.uz ga API orqali JSON so'rov beriladi.
So'rovga misol:
    [
        { 
            "product_id": 1,
            "quantity" : 30 
        },
        { 
            "product_id": 2,
            "quantity" : 20 
        }
    ]
*/
Route::post('warehouses', [WarehouseController::class,'index']);