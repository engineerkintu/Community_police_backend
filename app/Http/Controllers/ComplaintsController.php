<?php

namespace App\Http\Controllers;

use App\User;
use App\Complaints;

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

class ComplaintsController extends Controller
{
    //
    protected $user;
    
    protected $complaints;
    
    protected $key;

    public function __construct(User $user, MailService $mailService, ValidationService $validationService, Complaints $complaints){
        $this->user 					= $user;
        $this->mailService 		        = $mailService;
       
        $this->validationService 		= $validationService;
        $this->complaints               =$complaints;
      
        
    }

    public function addComplaint(Request $request){
        $input = $request->all();
        if(!$this->validationService->isValid($input,'add_complaint')){
            return response()->json(['message' => $this->validationService->errors], 400);
        }

        try
        {
            $message = 'Complaint has been created. Please wait for feedback';
            $complaints = $this->complaints->addComplaint($input);
            return response()->json([
                'message'           => $message,
                'status_code'       =>100,
                'status'            =>'Success',
                'complaint'         =>$complaints
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
    public function getCivilianComplaints(Request $request){
        $input = $request ->all();
        if(!$this->validationService->isValid($input,'get_civilian_complaints')){
            return response()->json(['message' =>$this->validationService->errors], 202);
        }
        try{
            $results = $this->complaints->getCivilianComplaints($input);
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

    public function getAllComplaints(Request $request){
        $input = $request -> all();
        
        try
        {
            $results = $this->complaints->getAllComplaints();
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

    public function getVillageComplaints(Request $request){
        $input = $request -> all();
        if(!$this->validationService->isValid($input,'get_village_complaints')){
            return response()->json(['message' =>$this->validationService->errors],202);
        }
        try{
            $results = $this->complaints->getVillageComplaints($input);
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

    public function getComplaint(Request $request){
        $input = $request -> all();
        
        try{
            $results = $this->complaints->getComplaint($input);
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

    public function getCivilianComplaint(Request $request){
        $input = $request -> all();
        
        try{
            $results = $this->complaints->getCivilianComplaint($input);
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
