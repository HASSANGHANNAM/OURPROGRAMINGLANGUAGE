<?php

use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\cityController;
use App\Http\Controllers\Api\madeByController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\productController;
use App\Http\Controllers\Api\searchController;
use App\Http\Controllers\Api\WarehouseController;
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

Route::post('/login', [MainController::class, 'login']);
Route::post('/register/owner', [MainController::class, 'regesterowner']);
Route::post('/register/pharmace', [MainController::class, 'regesterpharmace']);


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('/logout', [MainController::class, 'logout']);
    Route::get('/profile', [MainController::class, 'profile']);
});


Route::post('/createWarehouse', [WarehouseController::class, 'createWarehouse']);
Route::post('/create_made_by', [madeByController::class, 'create_made_by']);
Route::post('/create_category', [categoryController::class, 'create_category']);
Route::post('/create_city', [cityController::class, 'create_city']);
Route::post('/create_product', [productController::class, 'create_product']);
Route::post('/putInfavorates', [productController::class, 'putInfavorates']);


Route::put('/acceptOwner', [MainController::class, 'acceptOwner']);
Route::put('/updateWarehouse', [WarehouseController::class, 'updateWarehouse']);
Route::put('/update_made_by', [madeByController::class, 'update_made_by']);
Route::put('/update_category', [categoryController::class, 'update_category']);
Route::put('/update_city', [cityController::class, 'update_city']);
Route::put('/update_product', [productController::class, 'update_product']);


Route::get('/getAllCitys', [cityController::class, 'getAllCitys']);
Route::get('/getAllCategorys', [categoryController::class, 'getAllCategorys']);
Route::get('/getAllProducts/{phamacist_id}', [productController::class, 'getAllProducts']);
Route::get('/getSingleProduct/{phamacist_id}/{products_id}', [productController::class, 'getSingleProduct']);
Route::get('/getAllFavorates', [productController::class, 'getAllFavorates']);
Route::get('/search_product', [searchController::class, 'search_product']);


Route::delete('/deleteFavorates/{id}', [productController::class, 'deleteFavorates']);
