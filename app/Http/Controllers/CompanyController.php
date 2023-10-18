<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use  App\Models\Companies; 
use  App\Models\Products; 
use  App\Models\Rows; 
use  App\Models\CategoryCompanyRelations; 
use  App\Models\ProductCompanyRelations; 
use Illuminate\Support\Str;

class CompanyController extends Controller
{
     
    public function list(Request $request) { 

        $paging = "";

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        } 

        $all = DB::select("select id from companies where is_deleted=0"); 
        $datas = DB::select("select id, name, address, email, phone, picture from companies where is_deleted=0".$paging); 
 
        foreach($datas as $data) { 
            $data->categories = DB::select("select c.id, c.name from category_company_relations r inner join sub_categories c 
                on r.category_id=c.id where r.company_id=? and r.is_deleted=0", [$data->id]); 
        }

        return response()->json([
            'data' => ["companies"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }
  
     
    public function getById($id) {

        $company = Companies::find($id);

        $categories = DB::select("select c.id, c.category_id as parentId from category_company_relations r 
            inner join sub_categories c on r.category_id=c.id where r.company_id=? and r.is_deleted=0", [$id]); 

        $company->parentCategory = count($categories)>0 ? $categories[0]->parentId : null;
        $company->categories = $categories;
        $company->centerId = null;
        if($company->row_id) {
            $company->centerId = (Rows::find($company->row_id))->center_id;
        }
 
        return response()->json([
            'data' => $company,
            'error' => null,
        ]);
 
    }

    public function setStatus(Request $request) {

        $company = Companies::find($request->companyId);
        $company->is_deleted = $request->status;
        
        if($company->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən məktəbin aktivliyi dəyişdirildi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function deleteImage($id) {

        $company = Companies::find($id);
        $company->picture = null;
        
        if($company->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən firmanın şəkili silindi"],
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

        $company = null;
        if($request->id) {
            $company = Companies::find($request->id);
        } else { 
            $company = new Companies;
        }
        $company->name = $request->name;
        $company->address = $request->address; 
        $company->email = $request->email; 
        $company->phone = $request->phone;  
        $company->r_person = $request->rPerson;  
        $company->r_number = $request->rNumber;  
        $company->uuid = (string) Str::uuid();

        if($request->rowId) {
            $company->row_id = $request->rowId;  
        }

        if($request->userId) {
            $company->user_id = $request->userId;  
        }

        if($request->addressId) {
            $company->address_id = $request->addressId;  
        }
        
        if($company->save()) {

            if(!$request->checkCategories && count($request->categoryIds)>0) {
                $oldCategories = DB::select("select id from category_company_relations where is_deleted=0 and company_id=?", [$company->id]);

                $relations = [];
                foreach ($request->categoryIds as $item)
                {    
                    array_push($relations, [ 
                        'category_id' => $item,
                        'company_id' => $company->id
                    ]);
                }
                
                $inserted = DB::table('category_company_relations')->insert($relations);

                if($inserted && count($oldCategories)>0) { 
                    $str = "";
                    foreach($oldCategories as $sp) { $str .= strlen($str)===0 ? $sp->id : ",".$sp->id; }
                    DB::select("DELETE FROM `category_company_relations` WHERE id in (".$str.")");
                }
            }
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


    public function addRelation(Request $request) {

        $product = Products::find($request->rowId);

        $row = new ProductCompanyRelations();
 
        $row->product_id = $request->rowId;
        $row->company_id = $request->companyId;

        if(filter_var($request->isPrice, FILTER_VALIDATE_BOOLEAN)) {
            $price = $request->value;
            $percentage =  $product->price !==0 ?  (100 - ($request->value*100/$product->price)) : 0;
        } else {
            $percentage = $request->value;
            $price = $product->price !==0 ?  ($product->price - ($request->value*$product->price/100)) : 0;
        }

        $row->percentage = $percentage;
        $row->price = $price;
       
        if($row->save()) {  
            return response()->json([
                'data' => ["message"=>"Firmalar əlavə olundu"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function deleteRelation($id) {

        $relation = ProductCompanyRelations::find($id);
  
        $relation->is_deleted = 1;
        
        if($relation->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən firma məhsuldan çıxarıldı"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function update($id, Request $request) {

        $school = Companies::find($id);

        $school->name = $request->name;
        $school->address = $request->address;
        if($request->userId) {
            $school->user_id = $request->userId;  
        }
        
        if($school->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən məktəbin məlumatları yeniləndi"],
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
            
            $row = Companies::find($request->rowId);

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

}
