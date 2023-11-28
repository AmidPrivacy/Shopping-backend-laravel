<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use App\Models\Products; 
use App\Models\Floors; 
use App\Models\Subscribers;  
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
     
    public function index() { 
 
        $menus = DB::select("select id, name, is_product, uuid from menus where is_deleted=0");

        foreach($menus as $menu) { 
            if($menu->is_product===0) {
                $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
                foreach($categories as $category) { 
                    $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
                }
            } else {
                $categories = [];
            }
            
            $menu->categories = $categories;
        }

        $centers = DB::select("select picture from centers where is_deleted=0 and picture IS NOT NULL");

        $bestSelling = DB::select("select id, uuid, name, price from products where is_deleted=0 and is_best_selling=1");
        $popularProducts = DB::select("select id, uuid, name, price from products where is_deleted=0 and is_popular=1");

        foreach($bestSelling as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? and is_deleted=0", [$product->id]);
        }

        foreach($popularProducts as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? and is_deleted=0", [$product->id]);
        }
  
        return view('home')->with(['data'=>[], "menus"=>$menus, "centers"=>$centers, 
                                "bestSelling"=>$bestSelling, "populars"=>$popularProducts]); 

    }

    public function searchByName(Request $request) {


        $all = DB::select("select id from products where is_deleted=0 and name like '%".$request->name."%'");

        $products = DB::select("select id, name, price, discount, star, uuid from products where is_deleted=0 and name like '%".$request->name."%'"); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? 
                                            and is_deleted=0", [$product->id]);
        }

 
        $menus = DB::select("select id, name, is_product, uuid from menus where is_deleted=0");
        foreach($menus as $menu) { 
            if($menu->is_product===0) {
                $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
                foreach($categories as $category) { 
                    $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
                }
            } else {
                $categories = [];
            }
            $menu->categories = $categories;
        } 

      
        return view('categories')->with(['category'=>null, "menus"=>$menus, "products"=>$products, 
                    "isParent"=>false, "currentPage" =>($request->offset??0+1), "totalCount"=>count($all)]); 
    }

    public function getById($id)
    {  

        $datas = DB::select("select p.id, p.uuid, p.name, p.description, p.price, p.discount, c.name as categoryName, c.uuid as categoryId, p.warranty, 
            p.star, p.created_at from products p left join sub_categories c on p.category_id=c.id where p.is_deleted=0 and p.uuid=? order by p.id desc", [$id]); 

        $companies = DB::select("select r.price as price,r.in_stock as in_stock,r.product_id as product_id,c.r_person as r_person,c.r_number as r_number,c.id as company_id, c.name as company_name
        from product_company_relations r inner join companies c on r.company_id = c.id
        where r.product_id = " . $datas[0]->id);

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
        

        $menus = DB::select("select id, name, is_product, uuid from menus where is_deleted=0");

        foreach($menus as $menu) { 
            if($menu->is_product===0) {
                $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
                foreach($categories as $category) { 
                    $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
                }
            } else {
                $categories = [];
            }
            $menu->categories = $categories;
        }

        // dd($companies);
 
        return view('product')->with(['data'=>$productInfo, "companies" => $companies, "menus"=>$menus]); 
    }

    public function getProductsByMenuId($id, Request $request)
    {  
        $menuInfo = DB::select("select id, name from menus where uuid like '".$id."'"); 
        
        if(count($menuInfo)===0) {
            return redirect("/");
        }
    

        $paging = " LIMIT ".($request->limit??10)." OFFSET ".($request->offset??0)*10;

 
        $all = DB::select("select p.id from menus m inner join products p on m.id=p.menu_id 
                                                                where p.is_deleted=0 and m.uuid like '".$id."'");

        $products = DB::select("select p.id, p.name, p.price, p.discount, p.star, p.uuid from menus m 
                    inner join products p on m.id=p.menu_id where p.is_deleted=0 and m.uuid like '".$id."'"); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? 
                                            and is_deleted=0", [$product->id]);
        }

        // dd($products);
        $menus = DB::select("select id, name, is_product, uuid from menus where is_deleted=0");
        foreach($menus as $menu) { 
            if($menu->is_product===0) {
                $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
                foreach($categories as $category) { 
                    $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
                }
            } else {
                $categories = [];
            }
            $menu->categories = $categories;
        } 

      
        return view('categories')->with(['category'=>$menuInfo, "menus"=>$menus, "products"=>$products, 
                    "isParent"=>false, "currentPage" =>($request->offset??0+1), "totalCount"=>count($all)]);  
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

 
        $menus = DB::select("select id, name, is_product, uuid from menus where is_deleted=0");
        foreach($menus as $menu) { 
            if($menu->is_product===0) {
                $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
                foreach($categories as $category) { 
                    $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
                }
            } else {
                $categories = [];
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
 

        $subcategories = DB::select("select c.id, c.name from categories p inner join sub_categories c on p.id=c.category_id where c.is_deleted=0 and p.uuid like '".$id."'"); 

  
        $paging = " LIMIT ".($request->limit??10)." OFFSET ".($request->offset??0)*10;


 
        $all = DB::select("select id from products where is_deleted=0 and parent_category_id=".$categoryInfo[0]->id);

        $products = DB::select("select id, name, price, discount, star, uuid from products 
                                where is_deleted=0 and parent_category_id=".$categoryInfo[0]->id.$paging); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? 
                                            and is_deleted=0", [$product->id]);
        }

         
        $menus = DB::select("select id, name, is_product, uuid from menus where is_deleted=0");
        foreach($menus as $menu) { 
            if($menu->is_product===0) {
                $categories = DB::select("select id, name, uuid from categories where menu_id=? and is_deleted=0", [$menu->id]);
                foreach($categories as $category) { 
                    $category->subs = DB::select("select id, name, uuid from sub_categories where category_id=? and is_deleted=0", [$category->id]);
                }
            } else {
                $categories = [];
            }
            $menu->categories = $categories;
        } 

        $mixedTypes = DB::select("select t.id, t.name from general_values v left join types t on v.type_id = t.id 
                    where v.is_deleted=0 and v.category_id=?",[$categoryInfo[0]->id]);
 
  

        $types = [];

        foreach($mixedTypes as $type) { 

            $type->values = DB::select("select id, name from general_values where is_deleted=0 and 
                category_id=? and type_id=?",[$categoryInfo[0]->id, $type->id]);

            $check = array_search($type->id, array_column($types, 'id'));
   
            if($check === false) { array_push($types, $type);  } 
        }

        
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

        $query .= "  group by p.id, p.name, p.price, p.discount, p.star, p.uuid"; 
 
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
 

        $all = DB::select("select p.id from products p inner join categories c on c.id=p.parent_category_id 
                        left join product_value_relations v on p.id=v.product_id where p.is_deleted=0 and 
                        c.uuid like '".$request->id."'".$queryAll);

        $products = DB::select("select p.id, p.name, p.price, p.discount, p.star, p.uuid from products p 
                    inner join categories c on c.id=p.parent_category_id left join product_value_relations v on 
                    p.id=v.product_id where p.is_deleted=0 and c.uuid like '".$request->id."'".$queryFilter); 

        foreach($products as $product) { 
            $product->images = DB::select("select name from product_images where product_id=? and is_deleted=0", [$product->id]);
        }

        $offset = $request->offset??0;
        $limit = ($offset+1)*10;

        return ["products"=>$products, "totalCount"=>count($all), "currentRange" => ($offset*10)." - ".$limit];

    }
 
    public function searchAutoComplete(Request $request) {
 
        $sql = "";
 
        if($request->search) {
            $sql .= " and p.name like "."'%$request->search%'";
        } 
 
        $datas = DB::select("select p.id, p.uuid, p.name, m.name as menu,
                            p.price, c.name as category from products p left join sub_categories c
                            on p.category_id=c.id left join menus m on p.menu_id=m.id
                            where p.is_deleted=0".$sql." order by p.id desc LIMIT 15 OFFSET 0");

        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);

    }

}