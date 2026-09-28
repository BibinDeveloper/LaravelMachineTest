<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['purchase_order_id', 'product_id', 'qty', 'net_amount'];

     public function createOrderItem($data)
    {
        return self::create($data);
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
