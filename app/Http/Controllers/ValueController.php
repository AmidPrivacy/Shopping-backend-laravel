<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use App\Models\GeneralValues;  
use App\Models\Types;  

class ValueController extends Controller
{
      
    public function list(Request $request) {

        $paging = "";

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        } 

        $all = DB::select("select g.id from general_values g inner join categories c on g.category_id=c.id inner join types t on g.type_id=t.id where g.is_deleted=0");

        $datas = DB::select("select g.id, g.name, c.name as category, t.name as type from general_values g inner join categories c 
                            on g.category_id=c.id inner join types t on g.type_id=t.id where g.is_deleted=0".$paging); 
  

        return response()->json([
            'data' => ["values"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }
   
    public function getById($id) {

        $schoolInfo = Companies::find($id);
 
        return response()->json([
            'data' => $schoolInfo,
            'error' => null,
        ]);
 
    }

    public function getTypes() {

        $datas = DB::select("select id, name from types where is_deleted=0"); 
 
        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);
 
    }

    public function getByCategoryId($id) {
 

        $datas = DB::select("select id, name from types where is_deleted=0");

        foreach ($datas as $item)
        {   
            $item->values = DB::select("select id, name from general_values where is_deleted=0 and type_id=? and category_id=?", [$item->id, $id]);
        } 
 
        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);
    }

    public function setStatus(Request $request) {

        $company = GeneralValues::find($request->id);
        $company->is_deleted = $request->status;
        
        if($company->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən dəyərin aktivliyi dəyişdirildi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    } 

    public function addType(Request $request) {

        $type = new Types;

        $type->name = $request->name;  
        
        if($type->save()) {
            return response()->json([
                'data' => ["message"=>"Tip sistemə əlavə olundu"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function setTypeStatus(Request $request) {

        $type = Types::find($request->id);
        $type->is_deleted = $request->status;
        
        if($type->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən tipin aktivliyi dəyişdirildi"],
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
 
        $values = [];
        foreach ($request->newValues as $item)
        {     
            if(strlen($item["sp"])>0){
                array_push($values, [ 
                    'category_id' => $request->categoryId,
                    'type_id' => $request->typeId,
                    'name' => $item["sp"]
                ]);
            }
          
        }
        
        $inserted = DB::table('general_values')->insert($values);

        if($inserted) {   
 
            return response()->json([
                'data' => ["message"=>"Dəyərlər sistemə əlavə olundu"],
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

        $relations = [];
        foreach ($request->companies as $item)
        {     
            array_push($relations, [ 
                'product_id' => $request->rowId,
                'company_id' => $item
            ]);
        }
        
        $inserted = DB::table('product_company_relations')->insert($relations);

        if($inserted) {  
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

    public function update($id, Request $request) {

        $school = Companies::find($id);

        $school->name = $request->name;
        $school->address = $request->address;
        
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