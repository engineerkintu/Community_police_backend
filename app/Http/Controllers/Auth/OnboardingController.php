<?php
namespace App\Http\Controllers\Auth;

use App\User;
use App\Otp;
//use App\Setting;


use App\ValidationService;
use Hash;
use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Auth\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use App\MailService;

class OnboardingController extends Controller
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
    protected $verificationService;
    
    protected $key;

    public function __construct(User $user, MailService $mailService, ValidationService $validationService){
        $this->user 					= $user;
        $this->mailService 		        = $mailService;
        
        $this->validationService 		= $validationService;
        
        
    }
    
    
    public function ResetPassword(Request $request)
    {
        $input = $request->all();
        
    	if(!$this->validationService->isValid($input,'verify_email_code')){
           return response()->json(['message' => $this->validationService->errors], 400); 
        } 
        try 
        {
            $response = $this->user->verifyResetCode($input);
            if($response){
            $this->user->updatePassword($input);
                return response()->json([
                                        'status_code'   =>100,
                                        'message'       =>'Your password has been successfully changed',
                                        'status'        =>'Success'
                                    ],200);
                
            }else{
            return response()->json([
                                        'status_code'   =>101,
                                        'message'=>'You have entered an invalid code',
                                        'status'        =>'Error'
                                    ],202); 
            }
            
        }
        catch(JWTException $e)
        {
        return response()->json([
                                    'message'       =>'An error occured while resetting password',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],200);
        } 
        
    }
    public function registerUser(Request $request){
        //return $this->user->find(1);
         $input = $request->all();
        if(!$this->validationService->isValid($input,'register_user')){
           return response()->json(['message' => $this->validationService->errors->first(),'status_code'   =>103,], 200); 
        }
        
        try 
        {
            $message = 'Account Successfully Created.';
            if(isset($input['avatar']))
            {
                $image = base64_decode($input['avatar']);
                $time = time();
                $image_url = 'user/img_'.$time.'_'.$input['user_id'].'.jpg';
                if(!file_put_contents($image_url,$image)){
                        return response()->json([
                                        'message'       =>'An error occured while saving image',
                                        'status_code'   => 101,
                                        'status'        =>'Error'
                                    ],200);
                }
                $input['avatar'] = $image_url;
            }
            /**
             * If the account type is not of that of a patient, then they must be activated first before they login
             * And so their status must be set to 0 at registration and to 10 at activation
             * An email must be sent to them to tell them of these actions so they can watch out for the email/sms 
             */

            $type = $input['account_type'];

            if($type != 1 ){
                // they need to be first activated
                $input['status'] = 0;
            }else{
                // activated at registration
                $input['status'] = 10;
            }

            $user = $this->user->registerUser($input);
            $credentials = $request->only('email_address','password');
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                                            'status' => 'Error',
                                            'message'=>'Could not authenticate you. Please try logging in with the details you just created',
                                            'status_code'=>102
                                            ], 200);
                }   
                $user = User::where('email_address','=',$request->input('email_address'))->get()->first();
               
                return response()->json(['status'=>'Success','status_code'=>100, 'message'=>$message,'user'=>$this->user->refactorUser($user,$token),'token'=>$token, 'type' => $user['account_type']],202); 
            
            if($type == 2 ){
                $credentials = $request->only('email_address','password');
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                                            'status' => 'Error',
                                            'message'=>'Could not authenticate you. Please try logging in with the details you just created',
                                            'status_code'=>102
                                            ], 200);
                }   
                $user = User::where('email_address','=',$request->input('email_address'))->get()->first();
            
                return response()->json(['status'=>'Success','status_code'=>100, 'message'=>$message,'user'=>$this->user->refactorUser($user,$token),'token'=>$token, 'type' => $user['account_type']],202); 
            }
            else
            {
                $credentials = $request->only('email_address','password');
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json([
                                            'status' => 'Error',
                                            'message'=>'Could not authenticate you. Please try logging in with the details you just created',
                                            'status_code'=>102
                                            ], 200);
                }   
                $user = User::where('email_address','=',$request->input('email_address'))->get()->first();
               
                return response()->json(['status'=>'Success','status_code'=>100, 'message'=>$message,'user'=>$this->user->refactorUser($user,$token),'token'=>$token, 'type' => $user['account_type']],202); 
            }
        }
        catch(JWTException $e)
        {
            return response()->json([
                                    'message'       =>'An error occured while saving user',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],200);
        } 

    }

    
    
    public function updateProfile(Request $request){
        $logged_user = JWTAuth::parseToken()->authenticate();
        $input = $request->all();
        if($input['user_id']!=$logged_user->id){
        return response()->json([
                                    'message'       =>'Invalid User. You security token and account info do not match',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],200);
        }
        if($input['email_address']!=$logged_user->email_address){
            $data = $this->user->findUserByMail($input['email_address']);
            if($data){
                       return response()->json([
                                    'message'       =>'The new email address you have provided already exists',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],200);
            }
        }
        
        $input = $request->all();
        try 
        {

        	$user = $this->user->updateProfile($input);
        }
        catch(JWTException $e)
        {
        return response()->json([
                                    'message'       =>'An error occured while updtating your profile',
                                    'status_code'   =>101,
                                    'status'        =>'Error',
                                ],200);
        } 
        return response()->json([
                                    'status_code'   =>100,
                                    'status'        =>'Success',
                                    'message'       => 'Your profile has been updated successfully.'
                                ],200);

    }

    public function LoginUser(Request $request){

        // grab credentials from the request
        $credentials = $request->only('email_address', 'password');

        try {

            if(!$request->input('email_address')||!$request->input('password')){
               return response()->json([
                                        'staus' => 'Error',
                                        'message'=>'Please provide an email address and password',
                                        'status_code'=>101
                                        ], 200); 
            }

            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                                        'status' => 'Error',
                                        'message'=>'Wrong email and/or password',
                                        'status_code'=>102
                                        ], 200);
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token','status_code'=>500], 500);
        }

        // all good so return the token
        $user = User::where('email_address','=',$request->input('email_address'))->get()->first();

        /**
         * Check of the users account is activated before they login
         */
        if($user->status != 10){
            return response()->json([
                                        'status' => 'Error',
                                        'message'=>'Your account is not yet active, Please wait for it to be activated',
                                        'status_code'=>102
                                        ], 200);
        }
        
        return response()->json(['status'=>'Success','status_code'=>100,'user'=>$this->user->refactorUser($user,$token),'token'=>$token],202);

    }

  

    public function refreshToken($user){
        $credentials = $request->only('email_address', 'password');
        // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                                        'status' => 'Error',
                                        'message'=>'Wrong password',
                                        'status_code'=>102
                                        ], 401);
            }
            return response()->json(['status'=>'Success','status_code'=>100,'token'=>$token],202);
    }

    public function updateProfilePhoto(Request $request){
        $logged_user = JWTAuth::parseToken()->authenticate();  
        $input = $request->all();
        if($input['user_id']!=$logged_user->id){
        return response()->json([
                                    'message'       =>'Invalid User. You security token and account info do not match',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],200);
        }
        $input = $request->all();    
        
        try 
        {
            if(isset($input['image']))
            {
            $image = base64_decode($input['image']);
            $time = time();
            $image_url = 'avatar/avatar_'.$time.'_'.$input['user_id'].'.jpg';
            $path_to_save = public_path().DIRECTORY_SEPARATOR.$image_url;
            if(!file_put_contents($path_to_save, $image)){
                    return response()->json([
                                    'message'       =>'An error occured while saving image',
                                    'status_code'   => 101,
                                    'status'        =>'Error'
                                ],200);
            }
            $input['avatar'] = $image_url;
            $user = $this->user->updateProfilePhoto($input);
            return response()->json([
                                    'status_code'   =>100,
                                    'status'        =>'Success',
                                    'image'         =>$image_url
                                ],200);
            }else{
                           return response()->json([
                                    'status_code'   =>101,
                                    'status'        =>'Error',
                                    'message'       =>'Please provide an image'
                                ],200); 
            }

        }
        catch(JWTException $e)
        {
        return response()->json([
                                    'message'       =>'An error occured while changing the user',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],200);
        } 

    }


    public function profileUser(Request $request){
        //return $this->user->find(1);
        $input = $request->all();
        if(!$this->validationService->isValid($input,'profile_user')){
           return response()->json(['message' => $this->validationService->errors], 400); 
        }
        
        try 
        {
        	$user = $this->user->profileUser($input);
        }
        catch(JWTException $e)
        {
        return response()->json([
                                    'message'       =>'An error occured while saving user',
                                    'status_code'   =>101,
                                    'status'        =>'Error'
                                ],400);
        } 
        return response()->json([
                                    'message'       =>'User Profiled',
                                    'status_code'   =>102,
                                    'status'        =>'Success',
                                    'user'          => $user
                                ],200);
    }

   
    public function activate($id){
        $user = $this->user->findUser($id);
        if($user){
            $this->user->ActivateUser($id); 
            $this->mailService->successfullyActivatedEmail($user);
            return 'Account has been successfully activated, you may close this page';
        }else{
            return 'User not known';
        }
    }
    public function validateOtp(Request $request){

        $code = $request->token;
        $user_id = $request->user_id;

        $check = Otp::where(['user' => $user_id, 'status' => 0, 'token' => $code])->count();

        if($check){

            Otp::where(['user' => $user_id, 'status' => 0, 'token' => $code])->update(['status' => 1]);

            return response()->json([
                'status_code'           =>100,
                'message'        => 'Success'
            ],200);



        }else{
            
            return response()->json([
                'status_code'           =>101,
                'message'        => 'Code is invalid, please try again'
            ],200);

        }

    }
    public function sendOtp(Request $request){

        $user = User::find($request->user_id);

        /**
         * First invalidate all pre-existing tokens
         */

        Otp::where(['user' => $user->id, 'status' => 0])->update(['status' => 1]);

        $token = substr(str_shuffle('123456789'), 0, 5); 

        $obj = [
            'user' => $user->id,
            'token' => $token,
            'status' => 0,
        ];

        Otp::create($obj);

        $this->mailService->sendOtp($user, $token);

        return;

    }
   
   
    public function activateCode($id){
        
        $user = $this->user->findUser($id);
        if($user){
            $this->user->ActivateUser($id); 
            $code = $user->store.rand(pow(10, 4-1), pow(10, 4)-1);;
            $this->user->generateCode($user->store,$code);
            $this->mailService->successfullyActivatedEmail($user, $code);
            return 'Account has been successfully activated and the code has been sent, you may close this page';
            // if($this->verificationService->sendCodeActivationSMS($user,$code)){
            // return 'Activation SMS to '.$user->first_name.' '.$user->last_name.'  with number '.$user->phone_number.'';
            // };
        }else{
            return 'User not known';
        }
    }

    public function token(){
        $token = JWTAuth::getToken();
        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{
        $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        return $this->response->withArray(['token'=>$token]);
    }


    public function checkPhonenumber($username){
        $fail = $this->user->checkPhonenumber($username);
        if($fail){
            return response()->json([
                'status_code'           =>0,
                'status_message'        =>'Phone Number Address already taken'
            ],401);
        }else{
            return response()->json([
                'status_code'           =>1,
                'status_message'        =>'Phone Number Available'
            ],200);
        }
    }
    public function checkEmail($email){
        $fail = $this->user->checkEmail($email);
        if($fail){
            return response()->json([
                'status_code'           =>0,
                'status_message'        =>'Email Address already taken'
            ],401);
        }else{
            return response()->json([
                'status_code'           =>1,
                'status_message'        =>'Email Available'
            ],200);
        }
    }
    public function getPolice(Request $request){
        $input = $request->all();
        try{
            $results = $this->user->getPolice($input);
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }
    public function getVillage(Request $request){
        $input = $request->all();
        try{
            $results = $this->user->getVillage($input);
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }
    public function getVillages(){
        
        try{
            $results = $this->user->getVillages();
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }
    public function activateUser(Request $request){
        $input = $request->all();
        try{
            $results = $this->user->activateUser($input);
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }
    public function getUser(Request $request){
        $input = $request->all();
        try{
            $results = $this->user->getUser($input);
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }

    public function getOfficers(){
        
        try{
            $results = $this->user->getOfficers();
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }
    public function getAllPolice(){
        
        try{
            $results = $this->user->getAllPolice();
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }
    public function getOfficer(Request $request){
        $input = $request->all();
        try{
            $results = $this->user->getOfficer($input);
            return response()->json([
                'status'            =>'Success',
                'status_code'       =>100,
                'result'            =>$results
            ],200);
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status'            =>'Error',
                'Status_code'       =>102,
                'message'           =>'could not verify token'
            ],401);
        }
    }

}