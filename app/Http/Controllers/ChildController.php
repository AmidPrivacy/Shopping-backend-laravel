<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use  App\Models\User;
use  App\Models\Children;
use Illuminate\Support\Facades\Validator;

class ChildController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    **/
    public function __construct()
    {
        // $this->middleware('auth:api');
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
            
            $user = Children::find($request->userId);

            $filename = time().rand(3, 9). '.'.$request->file('images')[0]->getClientOriginalExtension();
            $request->file('images')[0]->move('uploads/', $filename);

            $user->picture = $filename;

            if($user->save()) {
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


    public function list() {

       $users = DB::select("select c.id, c.name, c.picture, c.gender, c.birth_date, u.name as parent, c.taxi_id, c.nanny_id, p.name as package
       from children c inner join users u on c.parent_id=u.id left join user_children u_c on c.id=u_c.child_id or u.id=u_c.user_id 
       left join packages p on c.package_id=p.id where c.status=1"); 

       foreach($users as $user) {
        $nanny = DB::select("select u.id, u.name from users u where u.status=1 and u.role='DAYE' and u.id=?", [$user->nanny_id]); 
        $taxi = DB::select("select u.id, u.name from users u where u.status=1 and u.role='TAKSI' and u.id=?", [$user->taxi_id]); 
        $user->nanny = count($nanny)>0 ? $nanny[0] : null;
        $user->taxi = count($taxi)>0 ? $taxi[0] : null;
        $user->groups = DB::select("select s.id, g.name from group_students s inner join groups g on s.group_id=g.id where s.status=1 and s.child_id=?", [$user->id]);
       }

        return response()->json([
            'data' => $users,
            'error' => null,
        ]);

    }

    public function getChildrenByParentId($id) {

        $children = DB::select("select c.id, c.name, c.picture, c.gender, c.birth_date
        from children c where c.status=1 and c.parent_id=?", [$id]); 
 
          
         return response()->json([
             'data' => $children,
             'error' => null,
         ]);
 
    } 

    public function subUsers() {

        $users = DB::select("select u.name, u.email, u.number, u.driving_license, u.picture, u.car_number,
                            u.address, p.name as parent from sub_users u inner join users p on u.user_id=p.id");
        return response()->json([
            'data' => $users,
            'error' => null,
        ]);
 
    }

    public function studentNotes() { 

        $notes = DB::select("select s.id, c.name as student, u.name as author, s.score, s.note from student_notes s inner join children c on s.child_id=c.id inner join users u on s.author_id = u.id where s.status=1"); 
  
         return response()->json([
             'data' => $notes,
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

    public function setPackage(Request $request) {
        $user = Children::find($request->userId);
        $user->package_id = $request->packageId;
        
        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən paket əlavə edildi"],
                'error' => null,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'error' => ["message"=>"Sistem xətası"],
            ]);
        }
    }

    public function addPerson(Request $request) {

        $user = Children::find($request->userId);

        if($request->nannyId !==null) {
            $user->nanny_id = $request->nannyId;
        } else if($request->taxiId !==null) {
            $user->taxi_id = $request->taxiId;
        } 
        
        if($user->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən uşağa dayə və ya sürücü əlavə edildi"],
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
        $user->birth_date = $request->birthDate;
        $user->phone = $request->phone;
        $user->parent_id = $request->parentId; 
        $user->gender = $request->gender; 
        
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
