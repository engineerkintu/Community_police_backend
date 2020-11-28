<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Auth\Auth;
use App\ValidationService;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    protected $user;
    public function __construct(User $user,ValidationService $validationService){
        $this->user         = $user;
        $this->validationService 		= $validationService;
    }



    public function ChangePassword(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $input = $request->all();
    	if(!$this->validationService->isValid($input,'change_password')){
           return response()->json(['message' => $this->validationService->errors], 400); 
        } 


            $user_id                        = $user->id;
            $oldpassword                    = $input['old_password'];
            $loginpassword                  = $input['password'];

            $user = User::where('id','=',$user_id)->get()->first();

            if(Hash::check($oldpassword,$user->password)){
            $user->password                 = Hash::make($loginpassword);   
            if($user->save()){
                        return response()->json([
                        'status'           =>true,
                        'status_code'       =>100,
                        'message'           =>'Password Changed Successfully',
                        ]);
                     }
            }
            else{
                    return response()->json([
                        'status'           =>'Error',
                        'status_code'       =>101,
                        'message'             =>'Invalid old password.'
                        ]);
            }
    }

    public function addToken(Request $request){
        $input = $request->all();
        $v = Validator::make($input, [
            'device_token'        => 'required',
            'uuid'                => 'required',
            'platform'            => 'required',
        ]);
        try 
        {
        if($v->passes()){

          $token = $this->user->addToken($input);
            // Authentication passed...
            return response()->json([
                'message'               =>'Token has been successfully created. It will be deleted once you logout of the app',
                'status_description'    =>'Token Created',
                'status_code'           =>100,
                'status'                =>'Success',
                'result'                =>$token
            ],200);
        }else{
            return response()->json([
                'message'       =>$v->messages(),
                'status_code'   =>102,
                'status'        =>'Error'
            ],401);
        }
        }
        catch(JWTException $e)
        {
        return response()->json([
                                    'message'       =>'An error occured resetting your password. Please try again',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],401);
        } 
    }
}