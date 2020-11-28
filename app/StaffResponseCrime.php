<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Hash;
use DB;

class StaffResponseCrime extends Model
{
    

    protected $fillable = [
        'crime',
        'user',
        'response',

    ];

    public function addStaffResponseCrime($data){
        // $this->crime            =$data['crime_id'];
        // $this->user             =$data['user_id'];
        // $this->response         =$data['response'];
        // $this->created_at   =new Carbon();
        // $this->updated_at   =new Carbon();
        // $this->save();
        DB::table('staff_response_crime')->insert(['crime' =>$data['crime_id'],'user' =>$data['user_id'],'response'=>$data['response'],'created_at'=> new Carbon, 'updated_at'=> new Carbon]);

        DB::table('crimes')->where('id','=',$data['crime_id'])->update(['status' => 1]);
        return $this;
    }

    public function getCrimeResponse($data){
        $results = DB::table('staff_response_crime')->where('crime','=',$data['crime_id'])->get();
        return $results;
    }

    public function getStaffResponseCrimes($data){
        $results = DB::table('staff_response_crime')->where('user','=',$data['user_id'])->get();
        return $results;
    }
    public function getCrimeResponseStaff($data){
        $results = DB::table('users')->where('id','=',$data['user_id'])->get();
        return $results;
    }
}
