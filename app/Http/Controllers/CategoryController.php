<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Categories;
use App\Models\SubCategories;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function categoryList(Request $request) {

        $paging = "";
        $sql = "";

        if($request->name) {
            $sql .= " and c.name like "."'$request->name%'";
        }

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        }

        $all = DB::select("select c.id from categories c left join menus m on c.menu_id=m.id where c.is_deleted=0".$sql);
        $datas = DB::select("select c.id, c.name, picture, m.name as menu from categories c left join menus m on c.menu_id=m.id where c.is_deleted=0".$sql." order by c.id desc".$paging);

        foreach($datas as $data) {
            $data->subCategories = DB::select("select id, name from sub_categories where category_id=? and is_deleted=0", [$data->id]);
        }

        return response()->json([
            'data' => ["categories"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }

    public function getById($id, Request $request) {

        if(filter_var($request->isParent, FILTER_VALIDATE_BOOLEAN)){
            $category = Categories::find($id);
        } else {
            $category = SubCategories::find($id);
        }


        return response()->json([
            'data' => $category,
            'error' => null,
        ]);

    }

    public function getSubCategories() {

        $datas = DB::select("select id, name from sub_categories where is_deleted=0");


        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);

    }

    public function getSubCategoriesByParentId($id) {

        $datas = DB::select("select id, name from sub_categories where is_deleted=0 and category_id=?",[$id]);

        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);

    }

    public function upload(Request $request) {

        $imagesName = [];
        $response = [];

        $validator = Validator::make($request->all(),
            [
                'images' => 'required',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "Validation error", "errors" => $validator->errors()]);
        }

        if($request->has('images')) {

            $row = Categories::find($request->rowId);

            $filename = time().rand(3, 9). '.'.$request->file('images')[0]->getClientOriginalExtension();
            $request->file('images')[0]->move('uploads/', $filename);

            $row->picture = $filename;

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

    public function setStatus(Request $request) {

        $category = Categories::find($request->categoryId);

        if($request->isStatus) {
            $category->is_deleted = $request->status;
        } else {
            $category->menu_id = $request->menuId;
        }

        if($category->save()) {
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

    public function setSubStatus(Request $request) {

        $category = SubCategories::find($request->categoryId);
        $category->is_deleted = $request->status;

        if($category->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən kateqoriyanın aktivliyi dəyişdirildi"],
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

        if($request->rowId) {
            $category = Categories::find($request->rowId);
        } else {
            $category = new Categories;
            $category->uuid = (string) Str::uuid();
        }

        $category->name = $request->name;


        if($category->save()){

            return response()->json([
                'data' => ["message"=>"Kateqoriya məlumatları sistemə əlavə olundu"],
                'error' => null,
            ]);

        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function addSubCategory(Request $request) {

        if($request->rowId) {
            $category = SubCategories::find($request->rowId);
        } else {
            $category = new SubCategories;
            $category->uuid = (string) Str::uuid();
            $category->category_id = $request->parentId;
        }

        $category->name = $request->name;


        if($category->save()){

            return response()->json([
                'data' => ["message"=>"Kateqoriya məlumatları sistemə əlavə olundu"],
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
