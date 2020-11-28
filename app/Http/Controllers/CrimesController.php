<?php

namespace App\Http\Controllers;

use App\User;
use App\Crimes;
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

class CrimesController extends Controller
{
    //
    protected $user;
    protected $crimes;
    
    protected $key;

    public function __construct(User $user, MailService $mailService, ValidationService $validationService, Crimes $crimes){
        $this->user 					= $user;
        $this->mailService 		        = $mailService;
        $this->validationService 		= $validationService;
        $this->crimes               =$crimes;
      
        
    }

    public function addCrime(Request $request){
        $input = $request->all();
        // if(!$this->validationService->isValid($input,'add_crime')){
        //     return response()->json(['message' => $this->validationService->errors], 400);
        // }

        try
        {
            $message = 'Crime has been created. Please wait for feedback';
            $crimes = $this->crimes->addCrime($input);
            return response()->json([
                'message'           => $message,
                'status_code'       =>100,
                'status'            =>'Success',
                'crimes'         =>$crimes
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
    public function getCivilianCrimes(Request $request){
        $input = $request ->all();
        if(!$this->validationService->isValid($input,'get_civilian_crimes')){
            return response()->json(['message' =>$this->validationService->errors], 202);
        }
        try{
            $results = $this->crimes->getCivilianCrimes($input);
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

    public function getAllCrimes(Request $request){
        $input = $request -> all();
        
        try
        {
            $results = $this->crimes->getAllCrimes($input);
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

    public function getVillageCrimes(Request $request){
        $input = $request -> all();
      
        try{
            $results = $this->crimes->getVillageCrimes($input);
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

    public function getCrime(Request $request){
        $input = $request -> all();
        
        try{
            $results = $this->crimes->getComplaint($input);
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

    public function getCivilianCrime(Request $request){
        $input = $request -> all();
        
        try{
            $results = $this->crimes->getCivilianComplaint($input);
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
