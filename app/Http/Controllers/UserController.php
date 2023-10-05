<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\SubUsers;
use App\Models\Notifications;
use App\Models\Roles;
use App\Models\UserChildren;
use App\Models\Otp;

class UserController extends Controller
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

    public function list(Request $request) {

        $query = "";
   
        if(strlen($request->name)) {
            $query .= " and u.name like '%$request->name%' ";
        }

        if(strlen($request->number)) {
            $query .= " and u.number like '%$request->number%' ";
        }

        if(strlen($request->email)) {
            $query .= " and u.email like '%$request->email%' ";
        }

        if(strlen($request->drivingLicense)) {
            $query .= " and u.driving_license like '%$request->drivingLicense%' ";
        }

        if(strlen($request->carNumber)) {
            $query .= " and u.car_number like '%$request->carNumber%' ";
        } 

        if(strlen($request->subject)) {
            $query .= " and s.name like '%$request->subject%' ";
        } 

        if(strlen($request->role)) {
            $query .= " and u.role like '%$request->role%' ";
        } 
 

       $users = DB::select("select u.id, u.name, u.email, u.number, 
                    u.picture, u.address, u.role from users u where u.status=1 ".$query);
       
  
        return response()->json([
            'data' => $users,
            'error' => null,
        ]);

    }

    public function getById($id) {

        $userInfo = User::find($id);
 
         return response()->json([
             'data' => $userInfo,
             'error' => null,
         ]);
 
    }

   
    public function getByRole(REQUEST $request) {
 
        $users = DB::select("select u.id, u.name, u.email, u.number, u.driving_license, u.picture, u.car_number,
                                                u.address, u.role from users u where u.status=1 and u.role=?", [$request->role]);

        return response()->json([
            'data' => $users,
            'error' => null,
        ]);
 
    }
    
    public function addRole(Request $request) {

        $user = User::find($request->userId);
        $user->role = $request->role;
        
        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən istifadəçiyə icazə əlavə olundu"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
        
    }

    public function setStatus(Request $request) {
        $user = User::find($request->userId);
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

    public function update(Request $request) {

        $user = User::find($request->id);

        $user->name = $request->name;
        $user->email  = $request->email ;
        $user->number = $request->phone; 
        $user->address = $request->address;
        $user->role = $request->role;
 
        if($user->referral_code ===null && $user->role==4) {
            $user->referral_code = (string) random_int(100000, 999999);
        }
        
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

   
    public function getNotificationsById($childIds) {

        $token = JWTAuth::getToken(); 
        $user = JWTAuth::toUser($token);

        if($user->role !== null) {

            $roleName = $user->role;

            $condition = "";

            if($childIds !=="null") {
                $condition = " where child_id in (".$childIds.")";
            }

            $notifications = DB::select("select n.id, c.name as child, u.name as responsible, o.name as object, t.name as type, n.latitude, 
                n.longitude, n.created_at from notifications n inner join objects o on n.object_id = o.id inner join children 
                c on n.child_id = c.id inner join types t on n.type_id = t.id  inner join users u on n.user_id = u.id".$condition);

            if($roleName === $this->userRoleList()[1] || $roleName === $this->userRoleList()[2]) {
                return response()->json([
                    'data' => null,
                    'error' => ["message"=>"Sizin bu əməliyyata icazəniz yoxdur"]
                ]);
            }  else {
                return response()->json([
                    'data' => $notifications,
                    'error' => null,
                ]);
            }
 
        }

        return response()->json([
            'data' => null,
            'error' => ["message"=>"Sizin bu əməliyyata icazəniz yoxdur"]
        ]);
 
    }

    public function addNotification(Request $request) {

        $notification = new Notifications;

        $notification->user_id = $request->userId;
        $notification->child_id = $request->childId;
        $notification->object_id = $request->objectId;
        $notification->type_id = $request->typeId;
        $notification->latitude = $request->latitude;
        $notification->longitude = $request->longitude;

        if($notification->save()) {
            return response()->json([
                'data' => ["message"=>"Əməliyyat uğurla tamamlandı"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }

    }

    public function userRoleList() {
        return ["ADMIN", "DAYE", "TAKSI", "VALIDEYN", "MUELLİM"];
    }
 
    public function fetchObjects() {

        $objects = DB::select("select id, name from objects where status=1");
        return response()->json([
            'data' => $objects,
            'error' => null
        ]); 
    }

    public function sendOtp(Request $request) {

        $users = User::where('number', '=', $request->number)->get();

        if(count($users)>0) {
            $user = $users[count($users)-1];

            $otp = new Otp();
            $otp->user_id = $user->id;
            $otp->code = rand ( 1000 , 9999 );
            
            if($otp->save()) {
                return response()->json([
                    'data' => "Kod göndərildi",
                    'error' => null,
                ]);
            } else {
                return response()->json([
                    'data' => null,
                    'error' => "Sistem xətası",
                ]);
            }
        }

        return response()->json([
            'data' => null,
            'error' => "Nömrə sistemdə tapılmadı",
        ]);

    }

    public function verifyOtp(Request $request) {

        $users = User::where('number', '=', $request->number)->get();

        if(count($users)>0) {

            $user = $users[count($users)-1];

            $otp = Otp::where('user_id', '=', $user->id)->where('code', '=', $request->code)->where('status', '=', 0)->get(); 
            
            if(count($otp)>0) {

                $verifiedOtp = $otp[count($otp)-1];

                $verifiedOtp->status = 1;

                if($verifiedOtp->save()) {
                    return response()->json([
                        'data' => "Qeydiyyat tamamlandı",
                        'error' => null,
                    ]);
                }  

                return response()->json([
                    'data' => null,
                    'error' => "Sistem xətası",
                ]);
                
            }

            return response()->json([
                'data' => null,
                'error' => "Kod tapılmadı, Zəhmət olmasa yendən kod göndərin",
            ]); 

        }

        return response()->json([
            'data' => null,
            'error' => "Nömrə sistemdə tapılmadı",
        ]); 

    }

}