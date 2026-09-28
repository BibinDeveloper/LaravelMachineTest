<?php

namespace App\Http\Controllers;

use App\Models\SystemError;

abstract class Controller
{
    public function saveError($module,$message)
    {
        (new SystemError())->createError(['module'=>$module,'error_message'=>$message,'url'=>url()->current()]);
    }
}
