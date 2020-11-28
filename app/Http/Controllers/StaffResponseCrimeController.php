<?php

namespace App\Http\Controllers;

use App\User;
use App\StaffResponseCrime;
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


use App\Http\Requests;

class StaffResponseCrimeController extends Controller
{
    //
     //
     protected $user;
     protected $staffResponseCrime;
     
     protected $key;
 
     public function __construct(User $user, MailService $mailService,  ValidationService $validationService, StaffResponseCrime $staffResponseCrime){
         $this->user 					= $user;
         $this->mailService 		        = $mailService;
         $this->validationService 		= $validationService;
         $this->staffResponseCrime       =$staffResponseCrime;
       
         
     }
 
     public function addStaffResponseCrime(Request $request){
         $input = $request->all();
         if(!$this->validationService->isValid($input,'add_staff_response_crime')){
             return response()->json(['message' => $this->validationService->errors], 400);
         }
 
         try
         {
             $message = 'Staff response for as crime has been created.';
             $staffResponseCrime = $this->staffResponseCrime ->addStaffResponseCrime($input);
             return response()->json([
                 'message'                   => $message,
                 'status_code'               =>100,
                 'status'                    =>'Success',
                 'staffResponseCrime'        =>$staffResponseCrime 
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
     public function getCrimeResponse(Request $request){
         $input = $request ->all();
         if(!$this->validationService->isValid($input,'get_crime_response')){
             return response()->json(['message' =>$this->validationService->errors], 202);
         }
         try{
             $results = $this->staffResponseCrime->getCrimeResponse($input);
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
 
     public function getStaffResponseCrimes(Request $request){
         $input = $request -> all();
         if(!$this->validationService->isValid($input,'get_staff_response_crimes')){
             return response()->json(['message' =>$this->validationService->errors],202);
         }
         try
         {
             $results = $this->staffResponseCrime ->getStaffResponseCrimes($input);
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
     public function getCrimeResponseStaff(Request $request){
         $input = $request->all();
         try{
             $results = $this->staffResponseCrime->getCrimeResponseStaff($input);
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
