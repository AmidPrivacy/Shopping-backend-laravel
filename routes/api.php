<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SpecificationController;
use App\Http\Controllers\ValueController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\BlockController; 
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


 
    // Route::get('/product/{id}', 'getById']);
    Route::post('register', [AuthController::class, 'register']); 
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('user-profile', [AuthController::class, 'me']);

    //User apis
    $router->get('/users', [UserController::class, 'list']); 
    $router->get('/user/{id}', [UserController::class, 'getById']); 
    $router->get('/users-with-role', [UserController::class, 'getByRole']); 
    $router->put('/user-role', [UserController::class, 'addRole']); 
    $router->get('/user-roles', [UserController::class, 'userRoleList']); 
    $router->get('/teachers/{id}', [UserController::class, 'getTeachersBySubjectId']); 
    $router->put('/user-status', [UserController::class, 'setStatus']); 
    $router->put('/user/{id}', [UserController::class, 'update']);  
    $router->post('/subuser', [UserController::class, 'addSubUser']); 
    $router->put('/subuser', [UserController::class, 'updateSubUser']); 
    
  

    //Product apis
    $router->get('/products', [ProductController::class, 'list']);
    $router->get('/product/{id}', [ProductController::class, 'getById']);  
    $router->post('/product', [ProductController::class, 'add']);
    $router->put('/product/{id}', [ProductController::class, 'update']); 
    $router->put('/product-status', [ProductController::class, 'setStatus']); 
    $router->put('/product-home-status', [ProductController::class, 'setHomeStatus']); 
    $router->post('/product-image', [ProductController::class, 'upload']);
    $router->get('/product-images/{id}', [ProductController::class, 'imageList']);
    $router->delete('/product-image/{id}', [ProductController::class, 'deleteImage']);
    
    
    
    
    //Company apis
    $router->get('/companies', [CompanyController::class, 'list']);  
    $router->get('/company/{id}', [CompanyController::class, 'getById']); 
    $router->post('/company', [CompanyController::class, 'add']);
    $router->put('/company/{id}', [CompanyController::class, 'update']); 
    $router->put('/company-status', [CompanyController::class, 'setStatus']); 
    $router->put('/company-delete-image/{id}', [CompanyController::class, 'deleteImage']); 
    $router->post('/company-image', [CompanyController::class, 'upload']);
    $router->post('/company-relation', [CompanyController::class, 'addRelation']);
    $router->delete('/company-relation/{id}', [CompanyController::class, 'deleteRelation']);
    
    

    //Value apis
    $router->get('/menus', [MenuController::class, 'list']);   
    $router->post('/menu', [MenuController::class, 'add']); 
    $router->put('/menu-status', [MenuController::class, 'setStatus']);  


    
    //Category apis
    $router->get('/categories', [CategoryController::class, 'categoryList']);  
    $router->get('/category/{id}', [CategoryController::class, 'getById']); 
    $router->post('/category', [CategoryController::class, 'add']); 
    $router->post('/sub-category', [CategoryController::class, 'addSubCategory']);  
    $router->get('/sub-categories', [CategoryController::class, 'getSubCategories']); 
    $router->get('/sub-categories/{id}', [CategoryController::class, 'getSubCategoriesByParentId']); 
    $router->put('/category-status', [CategoryController::class, 'setStatus']); 
    $router->put('/subcategory-status', [CategoryController::class, 'setSubStatus']); 
    $router->post('/category-image', [CategoryController::class, 'upload']);

    //Specification apis
    $router->get('/specifications', [SpecificationController::class, 'list']);  
    $router->get('/specification/{id}', [SpecificationController::class, 'getById']); 
    $router->get('/sp-category/{id}', [SpecificationController::class, 'getByCategoryId']); 
    $router->post('/specification', [SpecificationController::class, 'add']); 
    $router->put('/specification-status', [SpecificationController::class, 'setStatus']); 

    //Value apis
    $router->get('/all-values', [ValueController::class, 'list']);  
    $router->get('/values/category/{id}', [ValueController::class, 'getByCategoryId']); 
    $router->get('/value-types', [ValueController::class, 'getTypes']); 
    $router->post('/value', [ValueController::class, 'add']); 
    $router->post('/value-type', [ValueController::class, 'addType']); 
    $router->put('/value-status', [ValueController::class, 'setStatus']); 
    $router->put('/type-status', [ValueController::class, 'setTypeStatus']); 

    //Block apis
    $router->get('/blocks', [BlockController::class, 'list']);  
    $router->get('/block/{id}', [BlockController::class, 'getById']); 
    $router->post('/block', [BlockController::class, 'add']); 
    $router->post('/block-floors', [BlockController::class, 'addFloor']); 
    $router->get('/block-floors/{id}', [BlockController::class, 'getFloorsByParentId']); 
    $router->put('/block-status', [BlockController::class, 'setStatus']); 
    $router->put('/floor-status', [BlockController::class, 'setFloorStatus']); 

    //Center apis
    $router->get('/centers', [CenterController::class, 'list']);  
    $router->get('/center/{id}', [CenterController::class, 'getById']); 
    $router->post('/center', [CenterController::class, 'add']); 
    $router->get('/center-rows/{id}', [CenterController::class, 'getRowsByParentId']); 
    $router->post('/center-row', [CenterController::class, 'addRow']);  
    $router->put('/center-status', [CenterController::class, 'setStatus']); 
    $router->put('/subcenter-status', [CenterController::class, 'setSubStatus']); 
    $router->post('/center-image', [CenterController::class, 'upload']);
    $router->put('/center-delete-image/{id}', [CenterController::class, 'deleteImage']); 
 

    $router->get('/floors', [CenterController::class, 'floorList']); 
    $router->get('/statistic-datas', [StatisticController::class, 'index']); 

        