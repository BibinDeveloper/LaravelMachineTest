<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'address'];

    public function receivedOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id')->where('purchase_order_status_id', 3);
    }
}
