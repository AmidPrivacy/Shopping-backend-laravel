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



// Route::group([
//     'prefix' => 'api'
// ], function ($router) {
//     Route::post('register', 'AuthController@register');
//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('user-profile', 'AuthController@me');

//     //User apis
//     $router->get('/users', 'UserController@list');
//     $router->get('/user/{id}', 'UserController@getById');
//     $router->get('/users-with-role', 'UserController@getByRole');
//     $router->put('/user-role', 'UserController@addRole');
//     $router->get('/user-roles', 'UserController@userRoleList');
//     $router->get('/teachers/{id}', 'UserController@getTeachersBySubjectId');
//     $router->put('/user-status', 'UserController@setStatus');
//     $router->put('/user/{id}', 'UserController@update');
//     $router->post('/subuser', 'UserController@addSubUser');
//     $router->put('/subuser', 'UserController@updateSubUser');



//     //Product apis
//     $router->get('/products', 'ProductController@list');
//     $router->get('/product/{id}', 'ProductController@getById');
//     $router->post('/product', 'ProductController@add');
//     $router->put('/product/{id}', 'ProductController@update');
//     $router->put('/product-status', 'ProductController@setStatus');
//     $router->put('/product-home-status', 'ProductController@setHomeStatus');
//     $router->post('/product-image', 'ProductController@upload');
//     $router->get('/product-images/{id}', 'ProductController@imageList');
//     $router->delete('/product-image/{id}', 'ProductController@deleteImage');




//     //Company apis
//     $router->get('/companies', 'CompanyController@list');
//     $router->get('/company/{id}', 'CompanyController@getById');
//     $router->post('/company', 'CompanyController@add');
//     $router->put('/company/{id}', 'CompanyController@update');
//     $router->put('/company-status', 'CompanyController@setStatus');
//     $router->put('/company-delete-image/{id}', 'CompanyController@deleteImage');
//     $router->post('/company-image', 'CompanyController@upload');
//     $router->post('/company-relation', 'CompanyController@addRelation');
//     $router->delete('/company-relation/{id}', 'CompanyController@deleteRelation');



//     //Value apis
//     $router->get('/menus', 'MenuController@list');
//     $router->post('/menu', 'MenuController@add');
//     $router->put('/menu-status', 'MenuController@setStatus');



//     //Category apis
//     $router->get('/categories', 'CategoryController@categoryList');
//     $router->get('/category/{id}', 'CategoryController@getById');
//     $router->post('/category', 'CategoryController@add');
//     $router->post('/sub-category', 'CategoryController@addSubCategory');
//     $router->get('/sub-categories', 'CategoryController@getSubCategories');
//     $router->get('/sub-categories/{id}', 'CategoryController@getSubCategoriesByParentId');
//     $router->put('/category-status', 'CategoryController@setStatus');
//     $router->put('/subcategory-status', 'CategoryController@setSubStatus');
//     $router->post('/category-image', 'CategoryController@upload');

//     //Specification apis
//     $router->get('/specifications', 'SpecificationController@list');
//     $router->get('/specification/{id}', 'SpecificationController@getById');
//     $router->get('/sp-category/{id}', 'SpecificationController@getByCategoryId');
//     $router->post('/specification', 'SpecificationController@add');
//     $router->put('/specification-status', 'SpecificationController@setStatus');

//     //Value apis
//     $router->get('/all-values', 'ValueController@list');
//     $router->get('/values/category/{id}', 'ValueController@getByCategoryId');
//     $router->get('/value-types', 'ValueController@getTypes');
//     $router->post('/value', 'ValueController@add');
//     $router->post('/value-type', 'ValueController@addType');
//     $router->put('/value-status', 'ValueController@setStatus');
//     $router->put('/type-status', 'ValueController@setTypeStatus');

//     //Block apis
//     $router->get('/blocks', 'BlockController@list');
//     $router->get('/block/{id}', 'BlockController@getById');
//     $router->post('/block', 'BlockController@add');
//     $router->post('/block-floors', 'BlockController@addFloor');
//     $router->get('/block-floors/{id}', 'BlockController@getFloorsByParentId');
//     $router->put('/block-status', 'BlockController@setStatus');
//     $router->put('/floor-status', 'BlockController@setFloorStatus');

//     //Center apis
//     $router->get('/centers', 'CenterController@list');
//     $router->get('/center/{id}', 'CenterController@getById');
//     $router->post('/center', 'CenterController@add');
//     $router->get('/center-rows/{id}', 'CenterController@getRowsByParentId');
//     $router->post('/center-row', 'CenterController@addRow');
//     $router->put('/center-status', 'CenterController@setStatus');
//     $router->put('/subcenter-status', 'CenterController@setSubStatus');
//     $router->post('/center-image', 'CenterController@upload');
//     $router->put('/center-delete-image/{id}', 'CenterController@deleteImage');


//     $router->get('/floors', 'CenterController@floorList');
//     $router->get('/statistic-datas', 'StatisticController@index');


// });
