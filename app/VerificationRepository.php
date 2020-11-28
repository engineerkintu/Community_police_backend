<?php
namespace App;


use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
class VerificationRepository
{

    protected $db;
    protected $mailer;
    protected $table = 'user_activations';
    protected $verification_table;

    public function __construct(Mailer $mailer,Connection $db)
    {
    	$this->mailer 				= $mailer;
        $this->db 					= $db;
    }
    	//generate a six character token
    	//microtime function reduces the occurances of duplication
    protected function getCode()
    {
        return substr(sha1(mt_rand() . microtime()), mt_rand(0,35), 6);;
    }

    public function createActivation($user)
    {

        $activation = $this->getActivation($user);

        if (!$activation) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);

    }

    public function sendSMSCode($phone_number)
    {
    	$this->verification_table = 'phone_verification';
    	$code = $this->generateCode($phone_number);
    	//perform actual sending of code
    }

    public function resendSMSCode($code)
    {
    	$this->verification_table = 'phone_verification';
    	$code = $this->regenerateCode($phone_number);
    }


    public function sendEmailCode($email_address){
    	$this->verification_table = 'email_verification';
    	$code = $this->generateCode($email_address);
        $this->mailer->send('email.verify',['code' => $code], function (Message $m) use ($email_address) {
            $m->to($email_address)->subject('Verification Code');
        });
    }

    public function resendEmailCode($email_address){
    	$this->verification_table = 'email_verification';
    	$code = $this->generateCode($email_address);
        $this->mailer->raw('email.verify',['code' => $code], function (Message $m) use ($user) {
            $m->to($user['email_address'])->subject('Verification Code');
        });
    }

    private function generateCode($field)
    {
    	$code = $this->getCode();
    	if($this->verification_table=='email_verification')
        {
	        $this->db->table($this->verification_table)->insert([
	            'email_address' 		=> $field,
	            'code' 					=> $code,
	            'created_at'			=> new Carbon(),
	            'updated_at'			=> new Carbon(),
	        ]);
    	}
    	else
    	{
	    	$this->db->table($this->verification_table)->insert([
	            'phone_number' 			=> $field,
	            'code' 					=> $code,
	            'updated_at'			=> new Carbon(),
	        ]);
    	}
        return $code;
    }

    private function regenerateCode($field)
    {

        $code = $this->getCode();
        if($this->verification_table=='email_verification')
        {
	        $this->db->table($this->verification_table)->where('email_address', $field)->update([
	            'code' 					=> $code,
	           	'created_at'			=> new Carbon(),
	            'updated_at'			=> new Carbon(),
	        ]);
    	}
    	else
    	{
	        $this->db->table($this->verification_table)->where('phone_number', $field)->update([
	            'code' 					=> $code,
	           	'updated_at'			=> new Carbon(),
	        ]);
    	}
        return $code;
    }
    
    public function checkVerificationBySMS($phone_number)
    {
        return $this->db->table('phone_verification')->where('phone_verification', $phone_number)->first();
    }
    
    public function checkVerificationByEmail($email_address)
    {
        return $this->db->table('email_verification')->where('email_address', $email_address)->first();
    } 

    public function getVerificationBySMS($code)
    {
        return $this->db->table('phone_verification')->where('code', $code)->first();
    }
    
    public function getVerificationByEmail($code)
    {
        return $this->db->table('email_verification')->where('code', $code)->first();
    }

    public function verifyEmail($code){
		$this->db->table('email_verification')->where('code', $code)->update(['verified' => true]);
    }

    public function deleteEmailActivation($email_address)
    {
        $this->db->table('email_verification')->where('email_address', $email_address)->delete();
    }
    public function deleteSMSActivation($phone_number)
    {
        $this->db->table('phone_verification')->where('phone_number', $phone_number)->delete();
    }
}