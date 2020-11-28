<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Appointment;
use App\OneSignalModel;
use Log;
use DateTime;
use Mail;
use App\User;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminders';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders according to specified times';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * Algorithm
         * - appointments are in the table named the same
         * - Fetch all accepted appointments whose start time is greater than current date
         * - for each of these appointments
         * - check if appointment is exactly 1 week ahead or if appointment is exactly 1 day ahead, if so
         * - get the doctor and the patient models
         * - send push notifications and email notifications to both patient and doctor 
         * -----Alvin Mukiibi 16th April 2020
         */

        $now = date('Y-m-d H:i:s');
        $flags = ["hour", "day", "week"];
        /**
         * Fetch all appointments that start at a time later than now
         */
        $appointments = Appointment::where('start_time', '>=', $now)->where(['status' => 1])->get();
        
        if(count($appointments)){

            foreach($appointments as $appointment){

                $start_time = strtotime($appointment->start_time);
                $today = strtotime($now);

                if(in_array("hour", $flags)){
                    $diff = round(($start_time - $today) / (60 * 60), 2);
                    $this->doTheSending($diff, $appointment);
                }
                if(in_array("day", $flags)){
                    $diff = round(($start_time - $today) / (60 * 60 * 24), 2);
                    $this->doTheSending($diff, $appointment);
                }
                if(in_array("week", $flags)){
                    $diff = round(($start_time - $today) / (60 * 60 * 24 * 7), 2);
                    $this->doTheSending($diff, $appointment);
                }
                
            }
        }
    }

    public function doTheSending($diff, $appointment){

        $obj = new OneSignalModel;

        if($diff >= 0.97 && $diff <= 1.00){
            $obj->sendAppointmentReminder($appointment); // send push notifications to both people
            $this->sendReminderMail($appointment->patient_id, $appointment->doctor_id,$appointment); // send email notifications
            $this->sendReminderMail($appointment->doctor_id, $appointment->patient_id, $appointment); // send email notifications
        }

    }
    public function sendReminderMail($from, $to, $appointment){
       
        $subject = 'Appointment Reminder';
       
        $user = User::find($to);
        $userfrom = User::find($from);
        // $userfrom = DB::table('users')->where('id',$appointment['user_id'])->first();
        // $email_address = $userto->email_address;
        // $user = DB::table('users')->where('id',$appointment['user_id'])->first();
        $appointment['start_time']  = date('F jS, Y h:i:s', strtotime($appointment['start_time']));
        $appointment['end_time']  = date('F jS, Y h:i:s', strtotime($appointment['end_time']));
        // $this->mailer->send('email.appointment',['userto' => $userto,'userfrom'=>$userfrom,'appointment'=>$appointment], function (Message $m) use ($email_address,$subject) {
        //     $m->to($email_address)->subject($subject);
        // });

        Mail::send('email.appointment', ['userto' => $user,'userfrom' => $userfrom, 'appointment'=>$appointment], function ($m) use ($user, $subject) {
            $m->to($user->email_address, $user->fullname())->subject($subject);
        });
    }
}
