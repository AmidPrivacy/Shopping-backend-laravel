<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Products;
use App\Models\SubCategories;
use App\Models\ProductImages;
use Illuminate\Support\Str;

class ProductController extends Controller
{

    public function list(Request $request) {

        $limit = isset($request->limit) ? $request->limit : 10;
        $offset = isset($request->offset) ? $request->offset*10 : 0;

        $sql = "";
        $companySql = "";

        if($request->id) {
            $sql .= " and p.id=".$request->id;
        }

        if($request->name) {
            $sql .= " and p.name=".$request->name;
        }

        if($request->name) {
            $sql .= " and p.name=".$request->name;
        }

        if($request->parentCategoryId) {
            $sql .= " and p.parent_category_id=".$request->parentCategoryId;
        }

        if($request->categoryId) {
            $sql .= " and p.category_id=".$request->categoryId;
        }

        if($request->price) {
            $sql .= " and p.price=".$request->price;
        }

        if($request->discount) {
            $sql .= " and p.discount=".$request->discount;
        }

        if($request->menuId) {
            $sql .= " and p.menu_id=".$request->menuId;
        }


        $allProducts = DB::select("select p.id from products p left join sub_categories c on p.category_id=c.id where p.is_deleted=0".$sql);

        $datas = DB::select("select p.id, p.uuid, p.name, p.isFront, m.name as menu, p.description, p.price, p.discount, c.name as category, p.warranty,
            p.star, p.created_at from products p left join sub_categories c on p.category_id=c.id left join menus m on p.menu_id=m.id
            where p.is_deleted=0".$sql." order by p.id desc LIMIT ? OFFSET ?", [$limit, $offset]);

        foreach($datas as $key => $data) {
            $data->companies = DB::select("select r.id, c.name, r.price as price, r.percentage as percentage from
                product_company_relations r inner join companies c on r.company_id=c.id where r.product_id=? and r.is_deleted=0", [$data->id]);
            // if($request->companyId && count($data->companies)===0) {  unset($datas[$key]);  }
        }

        foreach($datas as $data) {
            $data->specifications = DB::select("select p.id, s.name, p.value from product_specification_relations p
            inner join specifications s on p.specification_id=s.id where p.product_id=? and p.is_deleted=0", [$data->id]);
        }

        foreach($datas as $data) {
            $data->values = DB::select("select p.id, g.name, t.name as type from product_value_relations p
            inner join general_values g on p.value_id=g.id inner join types t on g.type_id=t.id
            where p.product_id=? and p.is_deleted=0", [$data->id]);
        }

        // dd($datas);

        return response()->json([
            'data' => ["products"=>$datas, "totalCount"=>count($allProducts)],
            'error' => null,
        ]);

    }


    public function imageList($id) {

        $datas = DB::select("select id, name as path from product_images where product_id=? and is_deleted=0 order by id desc", [$id]);


         return response()->json($datas);

     }


    public function getById($id) {


        $productInfo = Products::find($id);

        // $productInfo->parentCategory = (SubCategories::find($productInfo->category_id))->category_id;

        $productInfo->specifications = DB::select("select s.id, s.name, p.value from product_specification_relations p
        inner join specifications s on p.specification_id=s.id where p.product_id=? and p.is_deleted=0", [$id]);

        $productInfo->values = DB::select("select g.id, g.name, t.name as type from product_value_relations p
        inner join general_values g on p.value_id=g.id inner join types t on g.type_id=t.id
        where p.product_id=? and p.is_deleted=0", [$id]);


        return response()->json([
            'data' => $productInfo,
            'error' => null,
        ]);

    }


    public function setStatus(Request $request) {

        $product = Products::find($request->productId);
        $product->is_deleted = $request->status===0 ? 1 : 0;

        if($product->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən məhsulun aktivliyi dəyişdirildi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function AddMenu(Request $request) {

        $product = Products::find($request->rowId);
        $product->menu_id = $request->menuId;

        if($product->save()) {
            return response()->json([
                'data' => ["message"=>"Uğurlu əməliyyat"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function setHomeStatus(Request $request) {

        $product = Products::find($request->id);
        $product->isFront = $request->status;

        if($product->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən məhsulun statusu dəyişdirildi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function add(Request $request) {

        $product = null;

        if($request->id) {
            $product = Products::find($request->id);
        } else {
            $product = new Products;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->category_id = $request->categoryId;
        $product->parent_category_id = $request->parentCategoryId;
        $product->warranty = $request->warranty;
        $product->uuid = (string) Str::uuid();

        if($product->save()) {

            if(!$request->checkSps && count($request->specifications)>0) {
                $oldSps = DB::select("select id from product_specification_relations where is_deleted=0 and product_id=?", [$product->id]);
                $specifications = [];
                foreach ($request->specifications as $item)
                {
                    array_push($specifications, [
                        'specification_id' => $item["id"],
                        'value' => $item["value"],
                        'product_id' => $product->id
                    ]);
                }

                $insertedSp = DB::table('product_specification_relations')->insert($specifications);
                if($insertedSp && count($oldSps)>0) {
                    $str = "";
                    foreach($oldSps as $sp) { $str .= strlen($str)===0 ? $sp->id : ",".$sp->id; }
                    DB::select("DELETE FROM `product_specification_relations` WHERE id in (".$str.")");
                }
            }

            if(!$request->checkValues && count($request->values)>0) {
                $oldValues = DB::select("select id from product_value_relations where is_deleted=0 and product_id=?", [$product->id]);
                $values = [];
                foreach ($request->values as $item)
                {
                    array_push($values, [
                        'value_id' => $item,
                        'product_id' => $product->id
                    ]);
                }

                $insertedValue = DB::table('product_value_relations')->insert($values);
                if($insertedValue && count($oldValues)>0) {
                    $str = "";
                    foreach($oldValues as $value) { $str .= strlen($str)===0 ? $value->id : ",".$value->id; }
                    DB::select("DELETE FROM `product_value_relations` WHERE id in (".$str.")");
                }
            }

            return response()->json([
                'data' => ["message"=>"Məhsul məlumatları sistemə əlavə olundu"],
                'error' => null,
            ]);


        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function replaceCategory() {
        $products = DB::select("select id, parent_category_id as pCategory, category_id as categoryId from products where parent_category_id is null and category_id is not null and is_deleted=0");

        foreach ($products as $product)
        {
            $cat = SubCategories::find($product->categoryId);


            $pr = Products::find($product->id);
            $pr->parent_category_id = $cat->category_id;
            $pr->save();
            // dd($product->id);
        }

        echo "end....";
        // dd($products);
    }

    public function update($id, Request $request) {

        $product = Products::find($id);

        $product->name = $request->name;
        $product->floor = $request->floor;

        if($product->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən məhsulun məlumatları yeniləndi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function upload(Request $request) {

        $imagesName = [];
        $response = [];
        // return [$request->file()];
        $validator = Validator::make($request->all(),
            [
                'images' => 'required',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "Validation error", "errors" => $validator->errors()]);
        }

        if($request->has('images')) {

            $row = new ProductImages();

            $filename = time().rand(3, 9). '.'.$request->file('images')[0]->getClientOriginalExtension();
            $request->file('images')[0]->move('uploads/products/', $filename);

            $row->name = $filename;
            $row->product_id = $request->rowId;

            if($row->save()) {
                $response["status"] = 200;
                $response["message"] = "Success! image(s) uploaded";
            } else {
                $response["status"] = 500;
                $response["message"] = "Failed! image(s) not uploaded 1";
            }

        }

        else {
            $response["status"] = 500;
            $response["message"] = "Failed! image(s) not uploaded";
        }
        return response()->json($response);
    }


    public function deleteImage($id) {

        $img = ProductImages::find($id);
        $img->is_deleted = 1;
        // @unlink("uploads/products/".$img->name);
        if($img->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən məhsulun silindi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

}
