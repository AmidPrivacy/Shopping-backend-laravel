<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;  

class StatisticController extends Controller
{
    
    public function index(Request $request) {
 
        $users = DB::select("select id from users where status=1"); 
        $centers = DB::select("select id from centers where is_deleted=0");  
        $companies = DB::select("select id from companies where is_deleted=0");  
        $menus = DB::select("select id from menus where is_deleted=0");  
        $categories = DB::select("select id from categories where is_deleted=0");  
        $products = DB::select("select id from products where is_deleted=0");  
    
        return response()->json([
            'data' => ["users" => count($users), "centers" => count($centers), "firms" => count($companies), 
                       "menus" => count($menus), "categories" => count($categories), "products" => count($products)],
            'error' => null,
        ]);

    } 
     
   

}
