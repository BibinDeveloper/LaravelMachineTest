<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'total_amount',
        'gst',
        'amount_payable',
        'approved_by',
        'approved_at',
        'received_at',
        'created_by',
        'updated_by',
        'is_locked',
        'order_id',
        'purchase_order_status_id'
    ];

    public function status()
    {
        return $this->belongsTo(PurchaseOrderStatus::class, 'purchase_order_status_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function createOrder($data)
    {
        return self::create($data);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
