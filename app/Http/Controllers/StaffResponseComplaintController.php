<?php

namespace App\Http\Controllers;

use App\User;
use App\StaffResponseComplaints;
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

class StaffResponseComplaintController extends Controller
{
    //
    protected $user;
    protected $staffResponseComplaint;
    
    protected $key;

    public function __construct(User $user, MailService $mailService, ValidationService $validationService, StaffResponseComplaint $staffResponseComplaint){
        $this->user 					= $user;
        $this->mailService 		        = $mailService;
        $this->validationService 		= $validationService;
        $this->staffResponseComplaint   =$staffResponseComplaint;
      
        
    }

    public function addStaffResponseComplaint(Request $request){
        $input = $request->all();
        
        try
        {
            $message = 'Staff response for as complaint has been created.';
            $staffResponseComplaint = $this->staffResponseComplaint ->addStaffResponseComplaint($input);
            return response()->json([
                'message'                        => $message,
                'status_code'                    =>100,
                'status'                         =>'Success',
                'staffResponseComplaint'         =>$staffResponseComplaint 
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
    public function getResponseComplaint(Request $request){
        $input = $request ->all();
        
        try{
            $results = $this->staffResponseComplaint->getResponseComplaint($input);
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

    public function getStaffResponseComplaints(Request $request){
        $input = $request -> all();
        
        try
        {
            $results = $this->staffResponseComplaint ->getStaffResponseComplaints($input);
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

    public function getComplaintResponseStaff(Request $request){
        $input = $request->all();
        try{
            $results = $this->staffResponseComplaint->getComplaintResponseStaff($input);
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
