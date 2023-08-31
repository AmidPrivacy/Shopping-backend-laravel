<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Products;
use App\Models\SubCategories;
use App\Models\ProductImages;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{

    public function index()
    {

        $cart_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', Cookie::get('shopping_cart')), true );

        $menus = DB::select("select id, name from menus where is_deleted=0");

        foreach($menus as $menu) {
            $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
            foreach($categories as $category) {
                $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
            }
            $menu->categories = $categories;
        }

        // dd($cart_data);
        return view('cart')->with(['cart_data' => $cart_data, "menus"=>$menus]);
    }


    public function addtocart(Request $request)
    {

        $prod_id = $request->input('product_id');
        $quantity = $request->input('quantity');

        // dd(Cookie::get('shopping_cart'));

        if(Cookie::get('shopping_cart'))
        {
            $cart_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', Cookie::get('shopping_cart')), true );
        }
        else
        {
            $cart_data = array();
        }

        $item_id_list = array_column($cart_data, 'item_id');
        $prod_id_is_there = $prod_id;

        if(in_array($prod_id_is_there, $item_id_list))
        {

            foreach($cart_data as $keys => $values)
            {
                if($cart_data[$keys]["item_id"] == $prod_id)
                {
                    $cart_data[$keys]["item_quantity"] = $request->input('quantity');
                    $item_data = json_encode($cart_data);
                    $minutes = 60;
                    Cookie::queue(Cookie::make('shopping_cart', $item_data, $minutes));
                    return response()->json(['status'=>'"'.$cart_data[$keys]["item_name"].'" Already Added to Cart','status2'=>'2']);
                }
            }

        } else {

            $product = Products::where('uuid', $prod_id)->first();

            $images = DB::select("select id, name from product_images where product_id=? and is_deleted=0", [$product->id]);

            $prod_name = $product->name;
            $prod_image = count($images)>0 ? $images[0]->name : "";
            $priceval = $product->price;

            if($product)
            {
                $item_array = array(
                    'item_id' => $prod_id,
                    'item_name' => $prod_name,
                    'item_quantity' => $quantity,
                    'item_price' => $priceval,
                    'item_image' => $prod_image
                );
                $cart_data[] = $item_array;

                $item_data = json_encode($cart_data);
                $minutes = 60;

                // dd($item_data);
                Cookie::queue(Cookie::make('shopping_cart', $item_data, $minutes));
                return response()->json(['status'=>'"'.$prod_name.'" Added to Cart']);
            }
        }
    }

    public function loadBasket()
    {
        $cart_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', Cookie::get('shopping_cart')), true );

        echo json_encode(array('cart_data' => $cart_data)); die;
        return;
    }

    public function cartloadbyajax()
    {
        if(Cookie::get('shopping_cart'))
        {
            $cart_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', Cookie::get('shopping_cart')), true );
            $totalcart = count($cart_data);

            echo json_encode(array('totalcart' => $totalcart)); die;
            return;
        }
        else
        {
            $totalcart = "0";
            echo json_encode(array('totalcart' => $totalcart)); die;
            return;
        }
    }

    public function deletefromcart(Request $request)
    {
        $prod_id = $request->input('product_id');

        $cart_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', Cookie::get('shopping_cart')), true );

        $item_id_list = array_column($cart_data, 'item_id');
        $prod_id_is_there = $prod_id;

        if(in_array($prod_id_is_there, $item_id_list))
        {
            foreach($cart_data as $keys => $values)
            {
                if($cart_data[$keys]["item_id"] == $prod_id)
                {
                    unset($cart_data[$keys]);
                    $item_data = json_encode($cart_data);
                    $minutes = 60;
                    Cookie::queue(Cookie::make('shopping_cart', $item_data, $minutes));
                    return response()->json(['status'=>'Item Removed from Cart']);
                }
            }
        }
    }

    public function clearcart()
    {
        Cookie::queue(Cookie::forget('shopping_cart'));
        return response()->json(['status'=>'Your Cart is Cleared']);
    }


    public function successOrder()
    {
        //$this->clearcart();

        $menus = DB::select("select id, name from menus where is_deleted=0");

        foreach($menus as $menu) {
            $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
            foreach($categories as $category) {
                $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
            }
            $menu->categories = $categories;
        }

        // dd($cart_data);
        return view('success-order')->with(["menus"=>$menus]);
    }


}
