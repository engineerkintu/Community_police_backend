<?php
namespace App;
use DB;
use App\MedicalCenter;
use App\MedicalCenterUser;

use Carbon\Carbon;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
class MailService
{

    protected $mailer;
    protected $email_address;

    public function __construct(Mailer $mailer)
    {
    	$this->mailer 				= $mailer;
        $this->email_address        = 'customerservice@chaguzi.net';
    }

    public function successfullyActivatedEmail($user, $code = null){

        $email_address = $user->email_address;

        $this->mailer->send('email.account-activated-email',['user' => $user, 'code' => $code], function (Message $m) use ($email_address) {
            $m->to($email_address)->subject('Account activated');
        });


    }
    public function sendRegistrationEMail($data){
        
        
        
        // For a medical center
        $police = null;

        if($data->account_type == 2){
            $p_id = DB::table('staffs')->where(['user' => $data->id])->value('police');
            if($p_id){
                $police = DB::table('police')->find($p_id);
            }
        }

        $email_address = $this->email_address;
        /**
         * Send email to community police admin to activate this new account
         */
        $account_type = DB::table('account_type')->where('id', $data->account_type)->first();
        
        $this->mailer->send('email.register',['user' => $data, 'account_type' => 'Police Staff',  'police' => $police], function (Message $m) use ($email_address) {
            $m->to($email_address)->subject('New User Registration');
        });
        /**
         * Send email to email of registered user for instructions on whats next
         */
        $this->mailer->send('email.registered_user',['user' => $data], function (Message $m) use($data) {
            $m->to($data->email_address)->subject('Successfully Registered');
        });
    }
   

    public function sendMail($data,$case){
        /**
         * Depending on the args, we select a different email subject, and a different mail template
         * A. Mukiibi 27th April 2020
         */
        switch ($case) {
            case 'crime':
                $subject = 'New Crime';
                $template = 'email.crime';
                break;
            case 'crime_resp':
                $subject = 'Crime Response';
                $template = 'email.crime-response';
                break;
            case 'complaint':
                $subject = 'New Complaint';
                $template = 'email.complaint';
                break;
            case 'complaint_resp':
                $subject = 'Complaint Response';
                $template = 'email.complaint-response';
                break;
            default:
                return;
                break;
        }
        
        $userto = DB::table('users')->where('id',$data['userid'])->first();
        $userfrom = DB::table('users')->where('id',$data['user_id'])->first();
        $email_address = $userto->email_address;
        $user = DB::table('users')->where('id',$data['user_id'])->first();
        
        
        $this->mailer->send($template, ['userto' => $userto,'userfrom'=>$userfrom,'complaint'=>$data], function (Message $m) use ($email_address,$subject) {
            $m->to($email_address)->subject($subject);
        });
    }

    public function sendOtp($user, $token){

        $email_address = $user->email_address;

        $this->mailer->send('email.otp-confirm',['user' => $user,'token'=>$token], function (Message $m) use ($email_address) {
            $m->to($email_address)->subject('Token');
        });

    }   
   


    
}