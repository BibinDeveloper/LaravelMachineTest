<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrder;



class InventoryController extends Controller
{
   public function inventory(Request $request)
   {
      try{


         $validator=Validator::make($request->all(),['start'=>['required','numeric'],'length'=>['required','numeric']]);

         if($validator->fails())
            {
                return response()->json(['status'=>'error','message'=>join(".",$validator->errors()->all())],422);
            }


          $inventory=Product::where('is_active',true)->skip($request->start)->limit($request->length)->latest()->get();


          return response()->json(['status'=>'success','data'=>$inventory],200);




      }

      catch(\Exception $e)
      {

            $this->saveError("inventory_list",$e->getMessage());
            return response()->json(['status'=>'error','message'=>'Unable to load inventory'],500);
      }
   }

      public function inventoryLowStock(Request $request)
   {
      try{


         $validator=Validator::make($request->all(),['start'=>['required','numeric'],'length'=>['required','numeric']]);

         if($validator->fails())
            {
                return response()->json(['status'=>'error','message'=>join(".",$validator->errors()->all())],422);
            }


          $inventory=Product::where('is_active',true)->where('stock','<',10)->skip($request->start)->limit($request->length)->latest()->get();


          return response()->json(['status'=>'success','data'=>$inventory],200);




      }

      catch(\Exception $e)
      {

            $this->saveError("inventory_list",$e->getMessage());
            return response()->json(['status'=>'error','message'=>'Unable to load inventory'],500);
      }
   }

      
}
