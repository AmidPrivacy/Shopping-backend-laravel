<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use  App\Models\Menus; 
use Illuminate\Support\Str;

class MenuController extends Controller
{
     
    public function list(Request $request) {

        $query = "";
        if($request->type !== "null") {
            $query = " and is_product=".$request->type;
        }
        
        $datas = DB::select("select id, name, is_product from menus where is_deleted=0".$query." order by id desc"); 
        
        return response()->json([
            'data' => $datas,
            'error' => null,
        ]);
    
    }
     
    public function getById($id) {
    
        $subjectInfo = Menus::find($id);
    
        return response()->json([
            'data' => $subjectInfo,
            'error' => null,
        ]);
    
    }

    public function setStatus(Request $request) {

        $menu = Menus::find($request->menuId);
        $menu->is_deleted = $request->status;
        
        if($menu->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən menyunun aktivliyi dəyişdirildi"],
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

        if($request->id) {
            $menu = Menus::find($request->id);
        } else {
            $menu = new Menus;
            $menu->uuid = (string) Str::uuid();
        }
        
        $menu->name = $request->name; 
        $menu->is_product = $request->isProduct; 
        
        
        if($menu->save()) { 
            return response()->json([
                'data' => ["message"=>"Fən məlumatları sistemə əlavə olundu"],
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

        $subject = Menus::find($id);

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
