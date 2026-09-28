<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        try {


            return view('auth.login');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->saveError("login", $e->getMessage());

            echo "Something went wrong";
        }
    }

    public function doLogin(Request $request)
    {

        try {


            // $request->validate(['email'=>['required','string','exists:users,email','password'=>['required','string']]]);

            $Validator = Validator::make($request->all(), ['email'=>['required', 'string', 'exists:users,email'], 'password' => ['required', 'string']]);

            if ($Validator->fails()) {
                return response()->json(['status' => 'error', 'message' => join(".", $Validator->errors()->all())], 422);
            }



            $User = User::whereEmail($request->email)->where('user_type_id', 1)->latest()->first();


            if (!$User) {
                return response()->json(['status' => 'error', 'message' => "Invalid credentials"], 422);
            }


            if (Hash::check($request->password, $User->password)) {

                Auth::login($User);

                session()->flash('success','Logged in successfully');

                return response()->json(['status' => 'success', 'redirect_url' => route('dashboard')], 200);
            } else {


                return response()->json(['status' => 'error', 'message' => "Invalid credentials"], 422);
            }


            DB::beginTransaction();





            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->saveError('login_process', $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Unable to login']);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect(url('/'));
    }
}
