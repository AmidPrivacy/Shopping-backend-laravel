<?php

namespace App\Http\Controllers;

use App\Models\ProductImages;
use App\Models\Products;
use App\Spiders\KontaktHomeSpider;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RoachPHP\Roach;
use Illuminate\Support\Str;
use RoachPHP\Spider\Configuration\Overrides;

class ScraperController extends Controller
{

    public function __invoke(Request $request) {

        try {
            $result = Roach::collectSpider(KontaktHomeSpider::class, new Overrides(startUrls: [$request->input('url')]));
            $data = $result[0]->all();
        } catch (\Throwable $th) {
            return response()->json([
                'data' => null,
                'error' => ["message"=>$th->getMessage()],
            ]);
        }
        $catIds = explode('_', $request->input('category'));
        $categoryId = end($catIds);

        $product = new Products();
        $product->name = $data['title'];
        //$product->description = $request->description;
        //$product->price = $request->price;
        $product->discount = 0;
        $product->category_id = $categoryId;
        $product->warranty = 0;
        $product->uuid = (string) Str::uuid();

        if($product->save()) {

            if(count($data['images'])) {
                foreach ($data['images'] as $url) {
                    $row = new ProductImages();
                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                    if(Str::length($ext) > 5) {
                        continue;
                    }
                    $filename = time().rand(3, 9). '.'.$ext;
                    Storage::disk('custom')->put('products/'.$filename, file_get_contents($url));
                    $row->name = $filename;
                    $row->product_id = $product->id;
                    $row->save();
                }
            }


            foreach ($data['features'] as $feature) {
                //
            }
            return response()->json([
                'data' => ["message"=>"Məhsul sistemə əlavə olundu"],
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
