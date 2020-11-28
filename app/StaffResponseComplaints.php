<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Hash;
use DB;

class StaffResponseComplaints extends Model
{
    
    protected $fillable = [
        'complaint',
        'user',
        'response'
    ];

    public function addStaffResponseComplaint($data){
        
        DB::table('staff_response_complaint')->insert(['complaint' =>$data['complaint_id'],'user' =>$data['user_id'],'response'=>$data['response'],'created_at'=> new Carbon, 'updated_at'=> new Carbon]);

        DB::table('complaints')->where('id',$data['complaint_id'])->update(['status' => 1]);
        
    }

    public function getResponseComplaint($data){
        
        $results  = DB::table('staff_response_complaint')->where('complaint','=',$data['complaint_id'])->get();
        return $results;

    }

    public function getStaffResponseComplaints($data){
        $results = DB::table('staff_response_complaint')->where('user','=',$data['user_id'])->get();
        return $results;
    }
    public function getComplaintResponseStaff($data){
        $results = DB::table('users')->where('id','=',$data['user_id'])->get();
        return $results;
    }

}
