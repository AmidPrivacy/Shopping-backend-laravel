<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use  App\Models\Centers; 
use  App\Models\Rows;  
use Illuminate\Support\Str;

class CenterController extends Controller
{
     
    public function list(Request $request) { 

        $paging = "";

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        } 

        $all = DB::select("select id from centers where is_deleted=0"); 
        $datas = DB::select("select id, name, picture from centers where is_deleted=0".$paging); 

        foreach($datas as $data) { 
            $data->rows = DB::select("select id, name from `rows` where center_id=? and is_deleted=0", [$data->id]); 
        }
   
        return response()->json([
            'data' => ["centers"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }

    public function getRowsByParentId($id) {

        $rows = DB::select("select id, name from `rows` where is_deleted=0 and center_id=?",[$id]); 
        $addresses = DB::select("select id, address from `center_addresses` where is_deleted=0 and center_id=?",[$id]); 
        
        return response()->json([
            'data' => ["rows"=>$rows, "addresses"=>$addresses],
            'error' => null,
        ]);

    }

    public function floorList() {

        $datas = DB::select("select id, name from floors where is_deleted=0"); 
 
        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);

    }
     
    public function getById($id) {

        $centerInfo = Centers::find($id);

        $centerInfo->addresses = DB::select("select id, address, number, obj_number from center_addresses where is_deleted=0 and center_id=?", [$id]);
 
        return response()->json([
            'data' => $centerInfo,
            'error' => null,
        ]);
 
     }

    public function setStatus(Request $request) {

        $center = Centers::find($request->centerId);
        $center->is_deleted = $request->status;
        
        if($center->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən Mərkəz silindi"],
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

        $center = Centers::find($id);
        $center->picture = null;
        
        if($center->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən mərkəzin şəkili silindi"],
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

        $center = Rows::find($request->centerId);
        $center->is_deleted = $request->status;
        
        if($center->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən Mərkəz silindi"],
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

        $center = new Centers;

        $center->name = $request->name; 
        $center->uuid = (string) Str::uuid(); 
        
        if($center->save()) {

            $relations = [];
            foreach ($request->addresses as $item)
            {     
                array_push($relations, [ 
                    'center_id' => $center->id,
                    'address' => $item['address'],
                    'number' => $item['number'],
                    'obj_number' => $item['objNumber'],
                ]);
            }
            
            $inserted = DB::table('center_addresses')->insert($relations);

            if($inserted) {  
                return response()->json([
                    'data' => ["message"=>"Mərkəz məlumatları sistemə əlavə olundu"],
                    'error' => null,
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'error' => ["message"=>"Sistem xətası"],
                ]);
            } 
             
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function addRow(Request $request) {

        $rows = [];

        foreach ($request->selectedFloors as $item)
        {     
            array_push($rows, [ 
                'center_id' => $request->id,
                'floor_id' => strlen($request->floorId)>0 ? $request->floorId : null,
                'name' => $item, 
            ]);
        }
        
        $inserted = DB::table('rows')->insert($rows);

        if($inserted) {  
            return response()->json([
                'data' => ["message"=>"Sıra məlumatları sistemə əlavə olundu"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }


       
        if($row->save()){

            return response()->json([
                'data' => ["message"=>"Sıra məlumatları sistemə əlavə olundu"],
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

        $user = Centers::find($id);

        $user->name = $request->name; 
        $user->subject = $request->subject; 
        
        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən mərkəz məlumatları yeniləndi"],
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
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "Validation error", "errors" => $validator->errors()]);
        }

        if($request->has('images')) { 
            
            $row = Centers::find($request->rowId);

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
