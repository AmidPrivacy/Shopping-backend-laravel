<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use  App\Models\AsanCenters;
use  App\Models\Rows;
use Illuminate\Support\Str;

class AsanCenterController extends Controller
{

    public function list(Request $request) {

        $paging = "";

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        }

        $all = DB::select("select id from asan_centers where is_deleted=0");
        $datas = DB::select("select id, name from asan_centers where is_deleted=0".$paging);

        return response()->json([
            'data' => ["centers"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }


    public function getById($id) {

        $asanCenterInfo = AsanCenters::find($id);


        return response()->json([
            'data' => $asanCenterInfo,
            'error' => null,
        ]);

     }

    public function setStatus(Request $request) {

        $center = AsanCenters::find($request->centerId);
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

        if($request->id) {
            return $this->update($request->id, $request);
        }

        $center = new AsanCenters;

        $center->name = $request->name;

        if($center->save()) {

                return response()->json([
                    'data' => ["message"=>"Mərkəz sistemə əlavə olundu"],
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

        $user = AsanCenters::find($id);

        $user->name = $request->name;

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


}
