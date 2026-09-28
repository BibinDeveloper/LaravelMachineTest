<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrder;
use App\Models\Supplier;


use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function savePurchaseOrder(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'product' => ['required', 'array'],
                'supplier' => ['required', 'numeric', 'exists:suppliers,id'],

                'product.*' => ['required', 'numeric', 'exists:products,id'],
                'unit_price' => ['required', 'array'],
                'qty' => ['required', 'array']

            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => join(".", $validator->errors()->all())], 422);
            }

            $product_array = [];


            DB::beginTransaction();


            $unit_price_array = $request->unit_price;

            $qty_array = $request->qty;

            foreach ($request->product as $key => $product) {

                if (isset($qty_array[$key]) && isset($unit_price_array[$key])) {

                    $product_array[] = ['product_id' => $product, 'qty' => $qty_array[$key], 'unit_price' => $unit_price_array[$key]];
                } else {

                    DB::rollBack();

                    return response()->json(['status' => 'error', 'message' => 'Some fields are missing']);
                }
            }

            $sub_total = 0;


            foreach ($product_array as $key => $pa) {

                $Product = Product::find($pa['product_id']);
                $sub_total = $sub_total + (($Product->unit_price) * $qty_array[$key]);
            }

            $tax = ($sub_total) * 10 / 100;

            $grand_total = $sub_total + $tax;



            $Order = (new PurchaseOrder())->createOrder([
                'supplier_id' => $request->supplier,
                'total_amount' => $sub_total,
                'gst' => $tax,
                'amount_payable' => $grand_total,
                'purchase_order_status_id' => 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            foreach ($product_array as $key => $pa) {

                $Product = Product::find($pa['product_id']);


                (new PurchaseOrderItem())->createOrderItem([
                    'purchase_order_id' => $Order->id,
                    'product_id' => $Product->id,
                    'qty' => $qty_array[$key],
                    'net_amount' => (($Product->unit_price) * $qty_array[$key])
                ]);
                $sub_total = $sub_total + (($Product->unit_price) * $qty_array[$key]);
            }




            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Purchase order saved successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();


            $this->saveError("save_purchase_order", $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 500);
        }
    }


    public function updatePurchaseOrder(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), ['status_id' => ['required', 'numeric', 'exists:purchase_order_statuses,id']]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => join(".", $validator->errors()->all())], 422);
            }

            $PO = PurchaseOrder::with(['items', 'items.product'])->where('is_locked',false)->find($id);


            if (!$PO) {
                return response()->json(['status' => 'error', 'message' => "Order not exists"], 404);
            }

            DB::beginTransaction();

            if ($request->status_id == 3) {
                foreach ($PO->items as $po) {
                    $stock = $po->product->stock;

                    $updated_stock = $po->product->stock - $po->qty;

                    Product::where('id', $po->product->id)->update(['stock' => $updated_stock]);
                }
            }

            if ($request->status_id == 3 || $request->status_id == 4) {
                PurchaseOrder::whereId($PO->id)->update(['purchase_order_status_id' => $request->status_id, 'is_locked' => true]);
            }

            else{

             PurchaseOrder::whereId($PO->id)->update(['purchase_order_status_id' => $request->status_id]);


            }

            DB::commit();

            return response()->json(['status'=>'success','message'=>'Order updated successfully'],200);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->saveError("update_po", $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Unbale to update'], 500);
        }
    }

    public function ordersBySupplier(Request $request)
   {
      try{


       


          $orders=Supplier::with('receivedOrders')->where('is_active',true)->latest()->get();


          return response()->json(['status'=>'success','data'=>$orders],200);




      }

      catch(\Exception $e)
      {

            $this->saveError("inventory_list",$e->getMessage());
            return response()->json(['status'=>'error','message'=>'Unable to load inventory'],500);
      }
   }
}
