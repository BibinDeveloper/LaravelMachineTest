<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['product_name', 'description', 'stock', 'unit_price', 'is_active'];
}
