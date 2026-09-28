<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrderItem;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {


            $total_active_products = Product::where('is_active', true)->count();

            $total_low_stock_products = Product::where('stock', '<', 10)->where('is_active', true)->count();

            $total_expenditure = PurchaseOrder::wherePurchaseOrderStatusId(3)->where('is_active', true)->sum('amount_payable');

            $latest_orders = PurchaseOrder::with(['status', 'supplier'])->wherePurchaseOrderStatusId(3)->where('is_active', true)->latest()->limit(5)->get();


            return view('admin.dashboard', compact('total_active_products', 'total_low_stock_products', 'total_expenditure', 'latest_orders'));
        } catch (\Exception $e) {
            $this->saveError("dashboard", $e->getMessage());

            session()->flash("error", 'Dasboard is not loaded');

            echo "Something went wrong";
        }
    }

    public function addPurchaseOrder()
    {
        try {


            $suppliers = Supplier::where('is_active', true)->get();


            return view('admin.add_purchase_order', compact('suppliers'));
        } catch (\Exception $e) {
            $this->saveError("dashboard", $e->getMessage());

            session()->flash("error", 'purchase order form is not loaded');

            return redirect()->back();
        }
    }

    public function addRow()
    {
        try {

            $products = Product::where('is_active', true)->get();


            $content = " <tr><td class='ps-3'><select class='form-select bg-light border-0 product-select' name='product[]'>
                                                <option value='' selected disabled>Choose product...</option>";


            foreach ($products as $product) {
                $content .= "<option value='" . $product->id . "'>" . $product->product_name . "</option>";
            }




            $content .= " </select>
                                        </td>";
            $content .= "  <td>
                                            <input type='number' name='unit_price[]' class='form-control bg-light border-0 unit-price' value='0' readonly>
                                        </td>
                                        <td>
                                            <input type='number' name='qty[]' class='form-control bg-light border-0 qty-input' value='0' min='1'>
                                        </td>
                                        <td>
                                            <input type='text' name='item_total[]' class='form-control bg-light border-0 item-total' value='0.00' readonly>
                                        </td>
                                        <td class='text-end pe-3'>
                                            <button type='button' class='btn btn-light btn-sm text-danger rounded-circle p-2 remove-row' title='Remove'>
                                                <i class='bi bi-trash'></i>
                                            </button>
                                        </td>
                                    </tr>";


            return response()->json(['status' => 'success', 'content' => $content], 200);
        } catch (\Exception $e) {
            $this->saveError("dashboard", $e->getMessage());

            return response()->json(['status' => 'error', 'message' => "Something went wrong"], 200);
        }
    }


    public function loadProduct(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), ['product_id' => ['required', 'numeric', 'exists:products,id']]);

            if ($validator->passes()) {
                $product = Product::find($request->product_id);

                return response()->json(['status' => 'success', 'product' => $product], 200);
            }
        } catch (\Exception $e) {
        }
    }

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

            return response()->json(['status' => 'success', 'message' => 'Purchase order saved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();


            $this->saveError("save_purchase_order", $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 500);
        }
    }
}
