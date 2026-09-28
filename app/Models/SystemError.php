<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemError extends Model
{
    protected $fillable=['module','error_message','user_id','url'];

    public function createError($data)
    {
        return self::create($data);
    }
}
