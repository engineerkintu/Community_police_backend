<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\OneSignalModel;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class OneSignalController extends Controller
{
    //Constructor for the class

    public function __construct(OneSignalModel $oneSignalModel)
    {
        $this->oneSignalModel = $oneSignalModel;
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function sendChatNotification(Request $request)
    {
        
        $input = $request->all();
        $v = Validator::make($input,[
            'receiver_id'=>'required',
            'message'   =>'required',
        ]);

        if($v->passes()){
            $input['name']=$this->user->first_name;
            $response = $this->oneSignalModel->sendChatNotification($input); 
            return $response;
            if($response==1)
            $message = ['message'=>'Notification sent to user','status'=>'Success'];
            else
            $message = ['message'=>'Notification not sent to user','status'=>'Error'];
            return response()->json([
                'status_description' =>$message['message'],
                'status_code'        =>100,
                'status'             =>$message['status']
            ],200); 
        }
        else{
            return response()->json([
                'message'            =>$v->messages(),
                'status_description' =>'The following fields are required',
                'status_code'        =>101,
                'status'             =>'Error'
            ],401); 
        }
    }
}
