<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;



class PurchaseOrderStatus extends Model
{
    use SoftDeletes;

    protected $fillable = ['status_name'];
}
