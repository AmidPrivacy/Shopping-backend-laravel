<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use  App\Models\User;
use  App\Models\Children;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    // private $userRoles = [""];

    public function list() {

       $users = DB::select("select u.name, u.email, u.number, u.driving_license, u.picture, u.car_number,
       u.address, p.name as parent from sub_users u inner join users p on u.user_id=p.id");

        return response()->json([
            'data' => $users,
            'error' => null,
        ]);

    }

    public function getById($id) {

        $userInfo = Children::find($id);

         return response()->json([
             'data' => $userInfo,
             'error' => null,
         ]);

     }

    public function setStatus(Request $request) {
        $user = Children::find($request->userId);
        $user->status = $request->status;

        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən istifadəçinin aktivliyi dəyişdirildi"],
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

        $user = new Children;

        $user->name = $request->name;
        $user->birth_date  = $request->birthDate;
        $user->parent_id = $request->parentId;

        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Uşaq məlumatları sistemə əlavə olundu"],
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

        $user = Children::find($id);

        $user->name = $request->name;
        $user->birth_date  = $request->birthDate;

        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən istifadəçinin məlumatları yeniləndi"],
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
