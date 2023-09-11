<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;  
use App\Models\Subscribers;   

class SubscriberController extends Controller
{
    
  
    public function index(Request $request) {

        $paging = "";

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        }

        $all = DB::select("select id from subscribers where is_deleted=0");
        $datas = DB::select("select id, mail, created_at as date from subscribers where is_deleted=0 order by id desc".$paging);
  
        return response()->json([
            'data' => ["subscribers"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);
    }

    public function addSubscriber(Request $request) {

        $mail = new Subscribers;
        $mail->mail = $request->mail; 
        
        if($mail->save()) { 
            return response()->json([
                'data' => ["message"=>"Abunəliyiniz qeyd olundu!"],
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