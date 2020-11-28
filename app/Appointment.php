<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Validator;
use Carbon\Carbon;
use App\MedicalCenterUser;
class Appointment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
    */
    protected $table = 'appointments';

    protected $guarded = ['id'];

    public function searchUser($data){
      $query    = $data['q'];
      $medical_center_id = $data['medical_center_id'];
      $country_id = $data['country_id'];
      /**
       * if the person searching is a patient or a medical center user, return doctors, otherwise return patients
       */
      if($data['account_type']==1 || $data['account_type']==6){
          $account_type =4;
      }else{
          $account_type = 1;
      }
      $sql      = 'SELECT id, first_name, last_name, speciality, phone_number, account_type, avatar from users 
                   WHERE ((first_name LIKE "'.$query.'%" AND id!='.$data['user_id'].' AND account_type='.$account_type.')
                   OR (first_name LIKE "%'.$query.'%" AND id!='.$data['user_id'].' AND account_type='.$account_type.' )
                   OR (first_name LIKE "%'.$query.'" AND id!='.$data['user_id'].' AND account_type='.$account_type.')
                   OR (last_name LIKE "'.$query.'%" AND id!='.$data['user_id'].' AND account_type='.$account_type.')
                   OR (last_name LIKE "%'.$query.'%" AND id!='.$data['user_id'].' AND account_type='.$account_type.')
                   OR (last_name LIKE "%'.$query.'" AND id!='.$data['user_id'].' AND account_type='.$account_type.' )) AND status = 10 AND country = '.$country_id.' LIMIT 20';

      $datac     =  DB::select(DB::raw($sql));

      $returned = [];

      if($data['account_type'] == 6){

            foreach($datac as $row){
                $user_id = $row->id;
                if(MedicalCenterUser::where(['user_id' => $user_id, 'medical_center_id' => $data['medical_center_id']])->count()){
                    $returned[] = $row;
                }
            }
            return $returned;
      }

      return $datac;
    }

    public function addAppointment($data){
         if($data['account_type']==1){
             $this->status = 0;
             $this->patient_id = $data['user_id'];
             $this->doctor_id = $data['userid'];
         }else{
            $this->status = 1;
            $this->doctor_id = $data['user_id'];
            $this->patient_id = $data['userid'];
         }

         $this->title       = $data['title'];
         $this->start_time  = $data['start_time'];
         $this->end_time    = $data['end_time'];
         $this->end_time    = $data['end_time'];
         if(isset($data['allday']))
         $this->allday      = $data['allday'];
         $this->save();
         return $this;
    }

    public function getPatientAppointments($data){
         switch($data['type']){
           case 1:
           $sql = 'SELECT a.id, a.title, a.patient_id, b.first_name as p_first_name, b.last_name as p_last_name, 
                   a.doctor_id, c.first_name as c_first_name, c.last_name as c_last_name, a.start_time as startTime, a.end_time as endTime, a.allday, a.status, m.name as medical_center_name, s.name as service_name FROM `appointments` a
                   LEFT JOIN users b on a.patient_id=b.id
                   LEFT JOIN users c on a.doctor_id=c.id 
                   LEFT JOIN medical_center m on a.medical_center_id=m.id
                   LEFT JOIN service s on a.service_id=s.id
                   WHERE a.start_time>=NOW() AND a.patient_id='.$data['user_id'].' ORDER BY a.start_time DESC
                   LIMIT '.$data['limit'].' OFFSET '.$data['offset'].'';
                   break;
           case 2:
           $sql = 'SELECT a.id, a.title, a.patient_id, b.first_name as p_first_name, b.last_name as p_last_name, 
                   a.doctor_id, c.first_name as c_first_name, c.last_name as c_last_name, a.start_time as startTime, a.end_time as endTime, a.allday, a.status, m.name as medical_center_name, s.name as service_name FROM `appointments` a
                   LEFT JOIN users b on a.patient_id=b.id
                   LEFT JOIN users c on a.doctor_id=c.id 
                   LEFT JOIN medical_center m on a.medical_center_id=m.id
                   LEFT JOIN service s on a.service_id=s.id 
                   WHERE a.start_time<=NOW() AND a.patient_id='.$data['user_id'].' ORDER BY a.start_time DESC
                   LIMIT '.$data['limit'].' OFFSET '.$data['offset'].'';
                   break;
         }
         return DB::select(DB::raw($sql));
    }

    public function getDoctorAppointments($data){
        switch($data['type']){
          case 1:
          $sql = 'SELECT a.id, a.title, a.patient_id, b.first_name as p_first_name, b.last_name as p_last_name, 
                  a.doctor_id, c.first_name as c_first_name, c.last_name as c_last_name, a.start_time as startTime, a.end_time as endTime, a.allday, a.status, m.name as medical_center_name, s.name as service_name FROM `appointments` a
                  LEFT JOIN users b on a.patient_id=b.id
                  LEFT JOIN users c on a.doctor_id=c.id 
                  LEFT JOIN medical_center m on a.medical_center_id=m.id
                  LEFT JOIN service s on a.service_id=s.id
                  WHERE a.start_time>=NOW() AND a.doctor_id='.$data['user_id'].' ORDER BY a.start_time DESC
                  LIMIT '.$data['limit'].' OFFSET '.$data['offset'].'';
                  break;
          case 2:
          $sql = 'SELECT a.id, a.title, a.patient_id, b.first_name as p_first_name, b.last_name as p_last_name, 
                  a.doctor_id, c.first_name as c_first_name, c.last_name as c_last_name, a.start_time as startTime, a.end_time as endTime, a.allday, a.status, m.name as medical_center_name, s.name as service_name FROM `appointments` a
                  LEFT JOIN users b on a.patient_id=b.id
                  LEFT JOIN users c on a.doctor_id=c.id
                  LEFT JOIN medical_center m on a.medical_center_id=m.id
                  LEFT JOIN service s on a.service_id=s.id
                  WHERE a.start_time<=NOW() AND a.doctor_id='.$data['user_id'].' ORDER BY a.start_time DESC
                  LIMIT '.$data['limit'].' OFFSET '.$data['offset'].'';
                  break;
        }
          return DB::select(DB::raw($sql));
   }

   public function approveAppointment($data){
    DB::table('appointments')->where('id', $data['appointment_id'])
                                    ->update([
                                        'status' 		    => 1,
                                        ]);
            
    $appointment =  json_decode(json_encode(DB::table('appointments')->where('id',$data['appointment_id'])->first()), true);
    if($data['account_type']==1){
        $appointment['user_id'] = $appointment['patient_id'];
        $appointment['userid'] = $appointment['doctor_id'];
    }else{
        $appointment['userid'] = $appointment['patient_id'];
        $appointment['user_id'] = $appointment['doctor_id'];
    }
    return $appointment;
   }

   public function rescheduleAppointment($data){
       DB::table('appointments')->where('id', $data['appointment_id'])
                                        ->update([
                                            'status'        => 3,
                                            'start_time'    => $data['start_time'],
                                            'end_time'      => $data['end_time'],
                                            'allday'        => false,
                                        ]);
       $appointment =  json_decode(json_encode(DB::table('appointments')->where('id',$data['appointment_id'])->first()), true);
        if($data['account_type']==1){
            $appointment['user_id'] = $appointment['patient_id'];
            $appointment['userid'] = $appointment['doctor_id'];
        }else{
            $appointment['userid'] = $appointment['patient_id'];
            $appointment['user_id'] = $appointment['doctor_id'];
        $appointment['reason'] = $data['reason'];
        return $appointment;                         
        }
   }

 
   public function acceptAppointmentReschedule($data){
    DB::table('appointments')->where('id', $data['appointment_id'])
                                    ->update([
                                        'status' 		    => 0,
                                        ]);                  
    $appointment =  json_decode(json_encode(DB::table('appointments')->where('id',$data['appointment_id'])->first()), true);
    if($data['account_type']==1){
        $appointment['user_id'] = $appointment['patient_id'];
        $appointment['userid'] = $appointment['doctor_id'];
    }else{
        $appointment['userid'] = $appointment['patient_id'];
        $appointment['user_id'] = $appointment['doctor_id'];
    }
    
    return $appointment;
   }
   public function cancelAppointment($data){
    DB::table('appointments')->where('id', $data['appointment_id'])
                                    ->update([
                                        'status' 		    => 2,
                                        ]);
    $app = Appointment::find($data['appointment_id']) ;     
    if($app->medical_centre_appointment_id){ 
        DB::table('medical_center_appointments')->where('id', $app->medical_centre_appointment_id)
                                        ->update([
                                            'status' 		    => 2,
                                            ]);      
    }            
    $appointment =  json_decode(json_encode(DB::table('appointments')->where('id',$data['appointment_id'])->first()), true);
    if($data['account_type']==1){
        $appointment['user_id'] = $appointment['patient_id'];
        $appointment['userid'] = $appointment['doctor_id'];
    }else{
        $appointment['userid'] = $appointment['patient_id'];
        $appointment['user_id'] = $appointment['doctor_id'];
    }
    $appointment['reason']  = $data['reason'];
    return $appointment;
   }
   public function sendReminder($data){               
    $appointment =  json_decode(json_encode(DB::table('appointments')->where('id',$data['appointment_id'])->first()), true);
    if($data['account_type']==1){
        $appointment['user_id'] = $appointment['patient_id'];
        $appointment['userid'] = $appointment['doctor_id'];
    }else{
        $appointment['userid'] = $appointment['patient_id'];
        $appointment['user_id'] = $appointment['doctor_id'];
    }
    return $appointment;
   }

   
   public function addAvailability($data)
   {
       
       /**
        * Check if the doctor has already added availability for that specific day of the week
        * if yes, then we just update, otherwise we add a new record
        * Alvin 15th May 2020
        */
        
        $days = $data['day_of_week'];
        
        foreach($days as $day){
            $check = DB::table('doctor_availability')->where(['doctor_id' => $data['doctor_id'], 'day_of_week' => $day])->count();
            if($check){
                $record = DB::table('doctor_availability')->where(['doctor_id' => $data['doctor_id'], 'day_of_week' => $day])->first();
                DB::table('doctor_availability')->where('id', $record->id)->update([
                    'health_center'     =>$data['health_center'],
                    'start_time'        =>$data['start_time'],
                    'end_time'          =>$data['end_time'],
                    'updated_at'       =>new Carbon()
                ]);
            }else{

                $id =  DB::table('doctor_availability')->insertGetId([
                    'doctor_id'         =>$data['doctor_id'],
                    'health_center'     =>$data['health_center'],
                    'start_time'        =>$data['start_time'],
                    'end_time'          =>$data['end_time'],
                    'day_of_week'       =>$day,
                    'created_at'        =>new Carbon(),
                    'updated_at'        =>new Carbon()
                ]);
            }

        }
        

        return $this->selectDoctorAvailability($data['doctor_id']);
    }

   public function editAvailability($data)
   {
       DB::table('doctor_availability')->where('id',$data['id'])->update([
        'health_center'     =>$data['health_center'],
        'start_time'        =>$data['start_time'],
        'end_time'          =>$data['end_time'],
        'day_of_week'       =>$data['day_of_week'],
       ]);
       return $this->selectDoctorAvailability($data['doctor_id']);
   }

   public function selectDoctorAvailability($id)
   {
       $sql = "SELECT a.id, a.doctor_id,a.health_center, concat(b.first_name,' ',b.last_name) as doctor, a.start_time,
               a.end_time, a.day_of_week from doctor_availability a LEFT JOIN users b ON a.doctor_id=b.id WHERE a.doctor_id=".$id."";
       $data     =  DB::select(DB::raw($sql));
       return $data;
   }


   public function deleteAvailability($data)
   {
       DB::table('doctor_availability')->where('id',$data['id'])->delete();
   }

}