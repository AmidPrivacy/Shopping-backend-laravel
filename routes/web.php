<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index']);


Route::get('/product/{id}', [HomeController::class, 'getById']);
Route::get('/search', [HomeController::class, 'searchByName']);
Route::post('/search-autocomplete', [HomeController::class, 'searchAutoComplete']);
Route::get('/special-products/{id}', [HomeController::class, 'getProductsByMenuId']);
Route::get('/sub-categories/{id}', [HomeController::class, 'categories']);
Route::get('/categories/{id}', [HomeController::class, 'parentCategories']);
Route::post('/product-filter', [HomeController::class, 'productFilter']);

Route::post('/add-to-cart', [CartController::class, 'addtocart']);
Route::get('/load-cart-data', [CartController::class,'cartloadbyajax']);
Route::get('/cart', [CartController::class, 'index']);
Route::post('/order', [OrderController::class, 'add']);
Route::get('/success-order', [CartController::class, 'successOrder']);
Route::delete('/delete-from-cart', [CartController::class, 'deletefromcart']);
Route::get('/clear-cart', [CartController::class, 'clearcart']);

Route::get('/load-basket', [CartController::class, 'loadBasket']);



$router->get('/version', function () use ($router) {
    return $router->app->version();
});