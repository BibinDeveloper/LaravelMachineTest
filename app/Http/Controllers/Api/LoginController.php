<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try{


        $validator=Validator::make($request->all(),['email'=>['required','string','email','exists:users,email',
                                                    'password'=>['required','string']]]);

        if($validator->fails())
        {
            return response()->json(['status'=>'error','message'=>join(".",$validator->errors()->all())],200);
        }


        $User=User::whereEmail($request->email)->first();

        if(!$User)
            {
                return response()->json(['status'=>'error','message'=>'User not found']);
            }


            if(Hash::check($request->password,$User->password))
                {

             $token=$User->createToken("MPR")->plainTextToken;

             return response()->json(['status'=>'success','token'=>$token],200);


                }

                else{


                 return response()->json(['status'=>'error','message'=>'Invalid credentials']);



                }

          
           


        }

        catch(Request $request)
        {

        }
    }
}
