<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use  App\Models\Blocks; 
use  App\Models\Floors; 

class BlockController extends Controller
{
     
    public function list() {
    
        $datas = DB::select("select id, name from blocks where is_deleted=0 order by id desc"); 

        foreach($datas as $data) { 
            $data->floors = DB::select("select id, name from floors where block_id=? and is_deleted=0", [$data->id]); 
        }
     
        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);
    
    }
     
    public function getById($id) {
    
        $subjectInfo = Blocks::find($id);
    
        return response()->json([
            'data' => $subjectInfo,
            'error' => null,
        ]);
    
    }

    public function getFloorsByParentId($id) {
    
        $subjectInfo = DB::select("select id, name from floors where block_id=? and is_deleted=0", [$id]); 
    
        return response()->json([
            'data' => $subjectInfo,
            'error' => null,
        ]);
    
    }

    public function setStatus(Request $request) {

        $block = Blocks::find($request->id);
        $block->is_deleted = $request->status;
        
        if($block->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən blokun aktivliyi dəyişdirildi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function setFloorStatus(Request $request) {

        $floor = Floors::find($request->id);
        $floor->is_deleted = $request->status;
        
        if($floor->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən mərtəbənin aktivliyi dəyişdirildi"],
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

        $menu = new Blocks;
        $menu->name = $request->name; 
        
        if($menu->save()) { 
            return response()->json([
                'data' => ["message"=>"Blok məlumatları sistemə əlavə olundu"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function addFloor(Request $request) {

        $rows = [];

        for ($min = $request->min; $min <= $request->max; $min++) {
            array_push($rows, [ 
                'block_id' => $request->id,
                'name' => $min, 
            ]);
        }
        
        $inserted = DB::table('floors')->insert($rows);

        if($inserted) {  
            return response()->json([
                'data' => ["message"=>"Blok məlumatları sistemə əlavə olundu"],
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

        $subject = Blocks::find($id);

        $subject->name = $request->name;
        $subject->price = $request->price; 
        $subject->type = $request->type; 
        $subject->percent = $request->percent; 
        
        if($subject->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən fənnin məlumatları yeniləndi"],
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
