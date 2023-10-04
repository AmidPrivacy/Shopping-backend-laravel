<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use App\Models\Specifications;  

class SpecificationController extends Controller
{
      
    public function list(Request $request) {

        $paging = "";
        $sql = "";

        if($request->name) {
            $sql .= " and s.name like "."'$request->name%'";
        }

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        } 

        $all = DB::select("select s.id from specifications s where s.is_deleted=0".$sql); 

        $datas = DB::select("select s.id, s.name from specifications s where s.is_deleted=0".$sql.$paging); 
  

        return response()->json([
            'data' => ["specifications"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }


    public function searchSpecification(Request $request) {

        $datas = DB::select("select id, name from specifications  
        where is_deleted=0 and name LIKE '".$request->name."%' LIMIT 100 OFFSET 0"); 
  
        return response()->json([
            'data' => $datas,
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

    public function getByCategoryId($id) {

        $specifications = DB::select("select id, name from specifications where is_deleted=0 and category_id=?",[$id]); 
 
        return response()->json([
            'data' => $specifications,
            'error' => null,
        ]);
 
    }

    public function setStatus(Request $request) {

        $company = Specifications::find($request->spId);
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
 
    public function add(Request $request) {
 
        $sps = [];
        foreach ($request->sps as $item)
        {     
            if(strlen($item["sp"])>0){
                array_push($sps, [ 
                    'category_id' => $request->categoryId,
                    'name' => $item["sp"]
                ]);
            }
          
        }
        
        $inserted = DB::table('specifications')->insert($sps);

        if($inserted) {   
 
            return response()->json([
                'data' => ["message"=>"Firma məlumatları sistemə əlavə olundu"],
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