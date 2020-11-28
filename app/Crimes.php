<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

use Hash;
use DB;


class Crimes extends Model
{
    

    protected $fillable = [
        'village',
        'crime_detail',
        'user'

    ];
    
    public function addCrime($data){
        $this->village          =$data['village'];
        $this->crime_detail     =$data['crime_detail'];
        $this->user             =$data['user_id'];
        $this->status           = 0;
        $this->created_at       =new Carbon();
        $this->updated_at       =new Carbon();
        $this->save();
        return $this;
    }

    public function getCivilianCrimes($data){
        $results = DB::table('crimes')->where('user','=',$data['user_id'])->get();
        return $results;
    }

    public function getAllCrimes(){
        $results = DB::table('crimes')->get();
        return $results;
    }

    public function getVillageCrimes($village_id){
        $results = DB::table('crimes')->where('village','=',$village_id)->get();
        return $results;
    }

    public function getCrime($data){
        $results = DB::table('crimes')->where('id','=',$data['crime_id'])->get();
        return $results;
    }

    public function getCivilianCrime($data){
        $results = DB::table('users')->where('id','=',$data['user_id']);
    }
}
