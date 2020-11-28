<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Hash;
use DB;

class Complaints extends Model
{
    

    protected $fillable = [
        'village',
        'details',
        'subject',
        'user'
    ];

    public function addComplaint($data){
        $this->village      =$data['village'];
        $this->details      =$data['details'];
        $this->subject      =$data['subject'];
        $this->user         =$data['user_id'];
        $this->status       = 0;
        $this->created_at   =new Carbon();
        $this->updated_at   =new Carbon();
        $this->save();
        return $this;
    }

    public function getCivilianComplaints($data){
        $results = DB::table('complaints')->where('user','=',$data['user_id'])->get();
        return $results;
    }

    public function getAllComplaints(){
        $results = DB::table('complaints')->get();
        return $results;
    }

    public function getVillageComplaints($village_id){
        $results   = DB::table('complaints')->where('village','=',$village_id)->get();
        return $results;
    }

    public function getComplaint($data){
        $results = DB::table('complaints')->where('id','=',$data['complaint_id'])->get();
        return $results;
    }

    public function getCivilianComplaint($data){
        $results = DB::table('users')->where('id','=',$data['user_id']);
    }
}
