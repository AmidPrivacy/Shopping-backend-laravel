<?php

namespace App\Jobs;

use App\Models\Companies;
use App\Models\CompanyOrderItem;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderReceived implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $order = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_items = $this->order->items;
        foreach($order_items as $order_item) {

            $product = $order_item->product;

            $available_company = Companies::whereHas('products', function($query) use($product) {
                $query->where('product_id', $product->id);
            })->with(['products' => function($query) use($product) {
                $query->where('product_id', $product->id);
            }])->get()->first();

            for ($i=0; $i < $order_item->qty; $i++) {
                $companyOrderItem = new CompanyOrderItem;
                $companyOrderItem->company_id = $available_company->id;
                $companyOrderItem->order_id = $this->order->id;
                $companyOrderItem->item_id = $order_item->id;
                $companyOrderItem->product_id = $product->id;
                $companyOrderItem->price = $available_company->products->first()->pivot->price;
                $companyOrderItem->date = now();
                $companyOrderItem->save();
            }
            
        }
    }
}
