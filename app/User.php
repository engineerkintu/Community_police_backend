<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Hash;
use DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    //use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fname',
        'lname',
        'gender',
        'age',
        'avatar',
        'remember_token',
        'account_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function registerUser($data){
        $this->fname               = $data['first_name'];
        $this->lname               = $data['last_name'];
        $this->email_address               = $data['email_address'];
        $this->account_type        = $data['account_type'];
        $this->password            = Hash::make($data['password']);
        $this->gender              = $data['gender'];
        $this->status              = $data['status'];
        $this->contact             =$data['phone_number'];
        $this->save();
        $this->id                  = $this->id;   
        $data['user_id']           = $this->id;
        if($data['account_type'] == 2){
            $this->addPoliceStaff($data);
        }
        return $this;
    }

    public function refactorUser($user)
    {
        return [
                  'id'                  =>$user->id,
                  'fname'               =>$user->first_name,
                  'lname'               =>$user->last_name,
                  'email_address'       =>$user->email_address,
                  'contact'             =>$user->phone_number,
                  'account_type'        =>$user->account_type,
                  'gender'              =>$user->gender,
                  'account_type_name'   =>$this->getAccountType($user->account_type),
                  
                ];
    }

    public function getUser($user_id){
        $results = DB::table('users')->where('id','=',$user_id)->get();
        return $results;
    }

    public function login($data){
        $pass = User::where('email_address','=',$data['email_address'])->where('password','=',$data['password'])->first();
        if ($pass == null){
            return 0;
        }
        else{
            return 1;
        }
    }


    public function addPoliceStaff($data)
    {
      

        DB::table('staffs')->insert([
            'user'                   =>$data['user_id'],
            'police'                 =>$data['police_id'],
            'role'                   =>$data['role'],
            'created_at'             =>new Carbon(),
            'updated_at'             =>new Carbon(),
        ]);
    }

    

    public function postAvatar($data){
        //find if user already has data in his profile
        $user = User::where('id',$data['user_id'])->first();
        if($user)//delete old avatar to free up space.
        //Storage::delete($user->avatar);
        $user->avatar  =   $data['avatar_url'];
        $user->save();
      }

      public function fullname(){
        return $this->fname . ' ' . $this->lname;
    }

    public function verifyResetCode($data){
        $user = DB::table('email_reset')
                         ->where('email_address',$data['email_address'])
                         ->where('code',$data['code'])
                         ->get(); 
        if(count($user)>0){
        $user = DB::table('email_reset')
                         ->where('email_address',$data['email_address'])
                         ->where('code',$data['code'])
                         ->delete(); 
         return true;
        }else{
            return false;
        }
     }

    public function activateUser($user_id){
        $user = User::find($ser_id);
        $user->status=10;
        $user->save();
        return 'activated';
      }

    public function addToken($data)
    {
       $id = DB::table('device_token_manager')->insertGetId([
            'app_id'     =>$data['app_id'], 
            'token'          =>$data['device_token'],
            'device_id'      =>$data['uuid'],
            'device_type'    =>$data['platform'],
            'user_id'        =>$data['user_id'],
            'created_at'     =>new Carbon(),
            'updated_at'     =>new Carbon()
        ]);

        return DB::table('device_token_manager')->find($id);
    }

    public function addDistricts($data){
        foreach($data as $r){
            DB::table('districts')->insert([
                'd_name'         =>$r['d_name'],
                'created_at'     =>new Carbon(),
                'updated_at'     =>new Carbon()
            ]);
        }
    }
    public function getAllPolice(){
        return DB::table('police')->get();
    }
    public function getDistricts(){
        return DB::table('districts')->get();
    }
    public function addSubcounties($data){
        DB::table('subcounties')->insert([
            'sb_name'        =>$data['sb_name'],
            'district'       =>$data['district'],
            'created_at'     =>new Carbon(),
            'updated_at'     =>new Carbon()
        ]);
    }

    public function getSubcounties(){
        return DB::table('subcounties')->get();
    }

    public function addVillages($data){
        DB::table('villages')->insert([
            'v_name'         =>$data['v_name'],
            'parish'         =>$data['parish'],
            'created_at'     =>new Carbon(),
            'updated_at'     =>new Carbon()
        ]);
    }

    public function getVillages(){
        $results = DB::table('villages')->get();
        return $results;
    }

    public function addParishes($data){
        DB::table('parishes')->insert([
            'p_name'        =>$data['p_name'],
            'subcounty'       =>$data['subcounty'],
            'created_at'     =>new Carbon(),
            'updated_at'     =>new Carbon()
        ]);
    }

    public function getParishes(){
        return DB::table('parishes')->get();
    }

    public function verifyUser($data){
        $user = DB::table('users')->where('email_address',$data['email_address'])
                                         ->where('password', Hash::make($data['password']))
                                         ->first();
        if($user)
            return $user;
        else
            return null;
    }

    public function findUser($user){
        $user = User::find($user);
        if($user)
          return $user;
        else 
          return false;
    }

    private function getAccountType($id){
        $data = DB::table('account_type')->where('id',$id)->first();
        if($data){
        return $data->name;
        }
        return null;
      }
  

    public function checkPhonenumber($phone){
        $user = DB::table('users')->where('contact',$phone)
                                                ->orWhere('contact','256'.substr($phone,1))
                                                ->orWhere('contact','250'.substr($phone,1))
                                                ->orWhere('contact','254'.substr($phone,1))
                                                ->orWhere('contact','233'.substr($phone,1))
                                                ->orWhere('contact',substr($phone,1))
                                                ->orWhere('contact','0'.substr($phone,3))
                                                ->first();
        return $user ? true : false;
    }

    public function checkEmail($email){
        $user = DB::table('users')->where('email_address',$email)->first();
        return $user ? true : false;
    }

    public function getPolice($police_id){
        
        $results = DB::table('police')->where('id','=',$police_id)->get();
        return $results;
    }

    public function getVillage($village_id){
        $results = DB::table('villages')->where('id','=',$village_id)->get();
        return $results;
    }

    public function getOfficer($user_id){
        $results = DB::table('staffs')->where('user','=',$user_id)->get();
        return $results;
    }

    public function getOfficers(){
        $results = BD::table('users')->where('account_type','=',2)->get();
        return $results;
    }

  
}
