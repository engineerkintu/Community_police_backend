<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Auth\Auth;

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
    public function __construct(User $user){
        $this->user         = $user;
        
    }

    public function Login(Request $request){
        // grab credentials from the request
        $credentials = $request->only('phone_number', 'password');

        try {
            if(!$request->input('phone_number')||!$request->input('password')){
               return response()->json([
                                        'error' => 'missing_credentials',
                                        'message'=>'Please provide a phone number and password',
                                        'status_code'=>101
                                        ], 401); 
            }

            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                                        'error' => 'invalid_credentials',
                                        'message'=>'Wrong email and/or password',
                                        'status_code'=>102
                                        ], 401);
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token','status_code'=>500], 500);
        }

        // all good so return the token
        $user = User::where('email','=',$request->input('email'))->get()->first();
       
        return response()->json(['user'=>$this->user->refactorUser($user,$token),'token'=>$token],202);

    }

    public function Register(Request $request){
        //return $this->user->find(1);
        $input = $request->all();
        if(!$this->user->isValid($input,'register')){
           return response()->json(['message' => $this->user->errors], 400); 
        }

        $input['password'] = Hash::make($request->input('password'));
        $new_user = $this->user->create($input);

        try{
            $token = JWTAuth::fromUser($this->user->find($new_user->id));
            $created_user=$this->user->refactorUser($new_user,$token);
        
        }catch(JWTException $e){
            return response()->json([
                                    'error' => 'could_not_create_user',
                                    'message'=>'An Error Occured while creating user',
                                    'status_code'=>500
                                    ], 500);
        }
        $subscription = [
                            'user_id'=>$this->user->id,
                            'plan_id'=>1,
                            'ends_at'=>date('Y-m-d h:i:s', strtotime("+90 days"))
                        ];
        $this->subscription->logSubscription($subscription);
        return response()->json([
                                    'user' => $created_user
                                    ], 202);
    }

    public function ChangePassword(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $input = $request->all();
        if(!$this->user->isValid($input,'change_pass')){
           return response()->json(['message' => $this->user->errors], 400); 
        }


            $user_id                        = $user->id;
            $oldpassword                    = $input['old_password'];
            $loginpassword                  = $input['password'];
            $repeatpassword                 = $input['repeatpassword'];

            if($loginpassword!=$repeatpassword)
            return response()->json([
                        'success'           =>true,
                        'message'           =>'Password fields do not match',
                        ]);
            $user = User::where('id','=',$user_id)->get()->first();

            if(Hash::check($oldpassword,$user->password)){
            $user->password                 = Hash::make($loginpassword);   
            if($user->save()){
                        return response()->json([
                        'success'           =>true,
                        'message'           =>'Password Changed Successfully',
                        ]);
                     }
            }
            else{
                    return response()->json([
                        'success'           =>false,
                        'message'             =>'Invalid old password.'
                        ]);
            }
    }

    public function sendSMSVerifiationCode(Request $request)
    {
    	$input = $request->all();
    	if(!$this->validationService->isValid($input,'send_sms')){
           return response()->json(['message' => $this->validationService->errors], 400); 
        } 
        try 
        {
        	$this->verificationService->sendVerificationSMS($input);
        }
        catch(JWTException $e)
        {

        }   
    }
}