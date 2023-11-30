<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use  App\Models\Order;
use App\Models\Products;
use  App\Models\Rows;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class OrderController extends Controller
{

    public function list(Request $request) {

        $paging = "";

        if(isset($request->limit) && isset($request->offset)) {
            $paging = " LIMIT ".$request->limit." OFFSET ".$request->offset*10;
        }

        $all = DB::select("select id from orders where is_deleted=0");
        $datas = DB::select("select * from orders where is_deleted=0".$paging);

        return response()->json([
            'data' => ["orders"=>$datas, "totalCount"=>count($all)],
            'error' => null,
        ]);

    }

    public function getById($id) {

        $orderInfo = Order::find($id);

        $orderInfo->items = DB::select("select * from order_items where is_deleted=0 and order_id=?", [$id]);

        return response()->json([
            'data' => $orderInfo,
            'error' => null,
        ]);

     }

    public function setStatus(Request $request) {

        $order = Order::find($request->orderId);
        $order->is_deleted = $request->status;

        if($order->save()) {
            return response()->json([
                'data' => ["message"=>"Seçilən sifariş silindi"],
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


    public function add(Request $request) {

        $order = new Order;

        $order->fullname = $request->fullName;
        $order->phone = $request->phone;
        $order->email = $request->mail;
        $order->address = $request->address;
        $order->note = $request->note;
        $order->referral_code = $request->referral_code;
        $order->uuid = (string) Str::uuid();

        if($order->save()) {
            $relations = [];

            $cart_data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', Cookie::get('shopping_cart')), true );

            // foreach ($request->items as $item)
            foreach ($cart_data as $item)
            {
                $product = Products::where('uuid', $item['item_id'])->first();
                array_push($relations, [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'company_id' => $item['item_company_id'],
                    'qty' => $item['item_quantity'],
                    'uuid' => Str::uuid()
                ]);
            }

            $inserted = DB::table('order_items')->insert($relations);

            if($inserted) {
                return response()->json([
                    'data' => ["message"=>"Sifariş məlumatları sistemə əlavə olundu"],
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




    public function getOrderDetail($id) {

        $orderInfo = Order::find($id);

        $orderInfo->items = DB::select("select p.id, p.name, p.price, p.uuid from order_items o inner join products p
                                        on o.product_id=p.id where o.is_deleted=0 and o.order_id=?", [$id]);


        return response()->json([
            'data' => $orderInfo,
            'error' => null,
        ]);

     }

}
