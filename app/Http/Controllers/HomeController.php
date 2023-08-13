<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use  App\Models\Products; 
use  App\Models\Floors; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
     
    public function index() { 

        // dd(Cookie::get('shopping_cart'));


        $menus = DB::select("select id, name from menus where is_deleted=0");

        foreach($menus as $menu) { 
            $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
            foreach($categories as $category) { 
                $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
            }
            $menu->categories = $categories;
        }

        $centers = DB::select("select picture from centers where is_deleted=0 and picture IS NOT NULL");

        $products = DB::select("select id, uuid, name, price from products where is_deleted=0 and isFront=1");

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? and is_deleted=0", [$product->id]);
        }

        // dd((string) Str::uuid());
        // dd($products);
        return view('home')->with(['data'=>[], "menus"=>$menus, "centers"=>$centers, "products"=>$products]); 

    }


    public function getById($id)
    { 
        // $productInfo = Products::find($id);

        $datas = DB::select("select p.id, p.uuid, p.name, p.description, p.price, p.discount, c.name as categoryName, c.uuid as categoryId, p.warranty, 
            p.star, p.created_at from products p inner join sub_categories c on p.category_id=c.id where p.is_deleted=0 and p.uuid=? order by p.id desc", [$id]); 

         
        foreach($datas as $data) {  
            $data->specifications = DB::select("select p.id, s.name, p.value from product_specification_relations p 
            inner join specifications s on p.specification_id=s.id where p.product_id=? and p.is_deleted=0", [$data->id]); 
        }

        foreach($datas as $data) { 
            $result = DB::select("select p.id, g.name, t.name as type from product_value_relations p 
            inner join general_values g on p.value_id=g.id inner join types t on g.type_id=t.id
            where p.product_id=? and p.is_deleted=0", [$data->id]); 
            
            $values = []; 
            
            foreach($result as $item) { 
                
                $index = array_search($item->type, array_column($values, 'type'));
                if($index !==false){
                    $values[$index]->name .= ", ".$item->name;
                } else {
                    array_push($values, $item);
                }
                 
            }
             
            
            $data->values = $values;
        }
        
        foreach($datas as $data) { 
            $data->images = DB::select("select id, name from product_images where product_id=? and is_deleted=0", [$data->id]); 
        }
         
        $productInfo = null;

        if(count($datas)>0) {

            $productInfo = $datas[0];

            $productInfo->others = DB::select("select id, name, price  from products where is_deleted=0 and 
                                    category_id=? order by id desc limit 15", [$productInfo->categoryId]); 

            foreach($productInfo->others as $other) { 
                $other->images = DB::select("select id, name from product_images where product_id=? and is_deleted=0 limit 1", [$other->id]); 
            }

        } else {
            return redirect("/");
        }
        

        $menus = DB::select("select id, name from menus where is_deleted=0");

        foreach($menus as $menu) { 
            $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
            foreach($categories as $category) { 
                $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
            }
            $menu->categories = $categories;
        }

        // dd($productInfo);
        // dd(Cookie::get('shopping_cart'));
        return view('product')->with(['data'=>$productInfo, "menus"=>$menus]); 
    }

    public function categories($id, Request $request)
    {  
        $categoryInfo = DB::select("select id, name from sub_categories where uuid like '".$id."'"); 
       
        if(count($categoryInfo)===0) {
           return redirect("/");
        }
 
  
        $paging = " LIMIT ".($request->limit??10)." OFFSET ".($request->offset??0)*10;

 
        $all = DB::select("select p.id from sub_categories s_c inner join products p on s_c.id=p.category_id 
                                                                where p.is_deleted=0 and s_c.uuid like '".$id."'");

        $products = DB::select("select p.id, p.name, p.price, p.discount, p.star, p.uuid from sub_categories s_c 
                    inner join products p on s_c.id=p.category_id where p.is_deleted=0 and s_c.uuid like '".$id."'"); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? 
                                            and is_deleted=0", [$product->id]);
        }

 
        $menus = DB::select("select id, name from menus where is_deleted=0");
        foreach($menus as $menu) { 
            $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
            foreach($categories as $category) { 
                $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
            }
            $menu->categories = $categories;
        } 

      
        return view('categories')->with(['category'=>$categoryInfo, "menus"=>$menus, "products"=>$products, 
                    "isParent"=>false, "currentPage" =>($request->offset??0+1), "totalCount"=>count($all)]); 
    }

    public function parentCategories($id, Request $request)
    {  
        $categoryInfo = DB::select("select id, name from categories where uuid like '".$id."'"); 
       
        if(count($categoryInfo)===0) {
           return redirect("/");
        }
 

        $subcategories = DB::select("select c.id, c.name from categories p inner join sub_categories c on p.id=c.category_id where p.uuid like '".$id."'"); 

  
        $paging = " LIMIT ".($request->limit??10)." OFFSET ".($request->offset??0)*10;


   
 
        $all = DB::select("select p.id from categories c inner join sub_categories s_c on c.id=s_c.category_id 
                            inner join products p on s_c.id=p.category_id where p.is_deleted=0 and c.uuid like '".$id."'");

        $products = DB::select("select p.id, p.name, p.price, p.discount, p.star, p.uuid from categories c 
                                inner join sub_categories s_c on c.id=s_c.category_id inner join products p 
                                on s_c.id=p.category_id where p.is_deleted=0 and c.uuid like '".$id."'"); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? 
                                            and is_deleted=0", [$product->id]);
        }

 
        $menus = DB::select("select id, name from menus where is_deleted=0");
        foreach($menus as $menu) { 
            $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
            foreach($categories as $category) { 
                $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
            }
            $menu->categories = $categories;
        } 

        $types = DB::select("select t.id, t.name from general_values v inner join types t on v.type_id = t.id 
                    where v.is_deleted=0 and v.category_id=?",[$categoryInfo[0]->id]);
 
        foreach($types as $type) { 
            $type->values = DB::select("select id, name from general_values where is_deleted=0 and 
                category_id=? and type_id=?",[$categoryInfo[0]->id, $type->id]);
        }

        // dd($types);
        return view('categories')->with(['category'=>$categoryInfo, "menus"=>$menus, "products"=>$products, "isParent"=>true,
                "currentPage" =>($request->offset??0+1), "totalCount"=>count($all), "subcategories" => $subcategories,
                "currentRange" => ($request->offset??0)." - ".($request->limit??(count($all)<10 ? count($all) : 10)), "types" => $types
                ]); 
    }

    public function productFilter(Request $request)
    {  
 
        $paging = " LIMIT ".($request->limit??10)." OFFSET ".($request->offset??0)*10;

        $query = " and p.price BETWEEN ".($request->startPrice??0)." AND ".($request->endPrice??100000);
         
        if(isset($request->categoryIds) && strlen($request->categoryIds)>0) {
            $query .= " and p.category_id IN (".$request->categoryIds.")";
        }

        if(isset($request->valueIds) && strlen($request->valueIds)>0) {
            $query .= " and v.value_id IN (".$request->valueIds.")";
        }

        $query .= "  group by p.id"; 
 
        if($request->order=="0" || $request->order=="1" || $request->order=="2") {
            if($request->order=="0") {
                $query .= " order by p.id desc";
            } else if($request->order=="1") {
                $query .= " order by p.price desc";
            } else {
                $query .= " order by p.price asc";
            } 
        }

        $queryAll = $query;
        $queryFilter = $query.$paging;
 
        $all = DB::select("select p.id from categories c inner join sub_categories s_c on c.id=s_c.category_id 
                            inner join products p on s_c.id=p.category_id inner join product_value_relations v 
                            on p.id=v.product_id where p.is_deleted=0 and c.uuid like '".$request->id."'".$queryAll);

        $products = DB::select("select p.id, p.name, p.price, p.discount, p.star, p.uuid from categories c 
                                inner join sub_categories s_c on c.id=s_c.category_id inner join products p 
                                on s_c.id=p.category_id inner join product_value_relations v 
                                on p.id=v.product_id where p.is_deleted=0 and c.uuid like '".$request->id."'".$queryFilter); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? and is_deleted=0", [$product->id]);
        }

        return ["products"=>$products, "totalCount"=>count($all)];

    }

}