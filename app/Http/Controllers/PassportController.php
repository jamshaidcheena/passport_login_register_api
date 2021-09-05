<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $validated = array(
            'name'=> 'required',
            'email'=> 'required',
            'password'=> 'required'
        );
        $validator=Validator::make($request->all(),$validated);
        if($validator->fails())
        {
            return $validator->errors();
        }
        else
        {
            $user=New User();
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=bcrypt($request->password);
            $user->save();
            $token_result=$user->createToken('my-app-token')->accessToken;
            $user->name=$request->name;
            return response()->json(['code_status'=>200,'token'=>$token_result,'name'=>$user->name]);
        }
    }
    public function login(Request $request)
    {
            if(Auth::attempt([
                'email'=>$request->email,
                'password'=>$request->password
            ])){
                $user=Auth::user();
                $token_result=$user->createToken('my-app-token')->accessToken;
                return response()->json(['code_status'=>200,'token'=>$token_result]);
            }else{
                return ['result'=>'unauthorized User'];
            }
    }
}
