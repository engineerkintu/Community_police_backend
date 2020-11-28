<?php

namespace App;
use App\AfricasTalkingGateway;
use DB;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

class VerificationService
{


    protected $verificationRepo;
    protected $mailer;
    protected $resendAfter  = 24;
    protected $username     = 'agasha';
    protected $apiKey       = '5b4a3a1b6d9e3afa46eb36920d50271d7f1705f7859e48ecaabe4755a1f6f6b7';

    public function __construct(VerificationRepository $verificationRepo, Mailer $mailer)
    {
        $this->verificationRepo 	= $verificationRepo;
        $this->mailer 				= $mailer;
    }

    public function sendVerificationEmail($user)
    {
    	$email_address = $user['email_address'];
    	$verification = $this->shouldSendEmail($email_address);
        if ($verification === null) 
        {
          //this email has not been verified
        	$verificationsms = $this->verificationRepo->sendEmailCode($email_address);
        	return 'sent';
        }
        else if($verification->verified==false)
        {
        	$verificationsms = $this->verificationRepo->resendEmailCode($email_address);
        	return 'sent';
        }
        else
        {
        	return null;
        }
    }

    public function sendRegistrationEmail($user){
        $store = DB::table('pharmacy')->where('id',$user->store)->first();
        if($this->verificationRepo->sendRegistrationEmail($user,$store));
        return;
    }

    public function sendVerifiationSMS($user)
    {
            // Specify your login credentials
            $username   = $this->username;
            $apikey     = $this->apiKey;
            // NOTE: If connecting to the sandbox, please use your sandbox login credentials
            // Specify the numbers that you want to send to in a comma-separated list
            // Please ensure you include the country code (+256 for Uganda in this case)
            $recipients = $user['phone_number'];
            // And of course we want our recipients to know what we really do
            $message    = 'Your verication code is '.$user['code'];
            // Create a new instance of our awesome gateway class
            $gateway    = new AfricasTalkingGateway($username, $apikey);
            // NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
            /*************************************************************************************
                        ****SANDBOX****
            $gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
            **************************************************************************************/
            // Any gateway error will be captured by our custom Exception class below, 
            // so wrap the call in a try-catch block
            try 
            { 
            // Thats it, hit send and we'll take care of the rest. 
            $results = $gateway->sendMessage($recipients, $message);
            $response = [];
            foreach($results as $result) {
                $response[]=[
                                'status'=>$result->status
                            ];
                // // status is either "Success" or "error message"
                // echo " Number: " .$result->number;
                // echo " Status: " .$result->status;
                // echo " MessageId: " .$result->messageId;
                // echo " Cost: "   .$result->cost."\n";
            }
            return $response[0];
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            $response[]=[
                                'status'=>"Encountered an error while sending: ".$e->getMessage()
                        ];
            return $response;
            }
    }
    public function sendResetCodeSMS($user)
    {
            // Specify your login credentials
            $username   = $this->username;
            $apikey     = $this->apiKey;
            // NOTE: If connecting to the sandbox, please use your sandbox login credentials
            // Specify the numbers that you want to send to in a comma-separated list
            // Please ensure you include the country code (+256 for Uganda in this case)
            $recipients = $user['phone_number'];
            // And of course we want our recipients to know what we really do
            $message    = 'Your reset code is '.$user['code'];
            // Create a new instance of our awesome gateway class
            $gateway    = new AfricasTalkingGateway($username, $apikey);
            // NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
            /*************************************************************************************
                        ****SANDBOX****
            $gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
            **************************************************************************************/
            // Any gateway error will be captured by our custom Exception class below, 
            // so wrap the call in a try-catch block
            try 
            { 
            // Thats it, hit send and we'll take care of the rest. 
            $results = $gateway->sendMessage($recipients, $message);
            $response = [];
            foreach($results as $result) {
                $response[]=[
                                'status'=>$result->status
                            ];
                // // status is either "Success" or "error message"
                // echo " Number: " .$result->number;
                // echo " Status: " .$result->status;
                // echo " MessageId: " .$result->messageId;
                // echo " Cost: "   .$result->cost."\n";
            }
            return $response[0];
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            $response[]=[
                                'status'=>"Encountered an error while sending: ".$e->getMessage()
                        ];
            return $response;
            }
    }

    public function sendEmail($email,$message)
    {
            // And of course we want our recipients to know what we really do
            $results = [];
            $this->mailer->send('email.password_reset',['code' => $message], function (Message $m) use($email) {
                $m->to($email)->subject('Reset Code');
            });

            $results = [$email,$message];
            return $results;
           
    }
    public function sendResetCodeEmail($user)
    {
            
            $recipient = $user['email_address'];
            // And of course we want our recipients to know what we really do
            $code    = $user['code'];

            $results = $this -> sendEmail($recipient,$code);
            $response = [];
            foreach($results as $result){
                $response[] = [
                    'status' => 'Success'
                ];     
            }
            return $response[0];          
           
    }
    public function sendCodeActivationSMS($user,$code)
    {
            // Specify your login credentials
            $username   = $this->username;
            $apikey     = $this->apiKey;
            // NOTE: If connecting to the sandbox, please use your sandbox login credentials
            // Specify the numbers that you want to send to in a comma-separated list
            // Please ensure you include the country code (+256 for Uganda in this case)
            $recipients = $user->phone_number;
            // And of course we want our recipients to know what we really do
            $message    = 'Your account has been activated. Your stock upload code is '.$code.'. A trainer will be sent to you';
            // Create a new instance of our awesome gateway class
            $gateway    = new AfricasTalkingGateway($username, $apikey);
            // NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
            /*************************************************************************************
                        ****SANDBOX****
            $gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
            **************************************************************************************/
            // Any gateway error will be captured by our custom Exception class below, 
            // so wrap the call in a try-catch block
            try 
            { 
            // Thats it, hit send and we'll take care of the rest. 
            $results = $gateway->sendMessage($recipients, $message);
            $response = [];
            foreach($results as $result) {
                $response[]=[
                                'status'=>$result->status
                            ];
                // // status is either "Success" or "error message"
                // echo " Number: " .$result->number;
                // echo " Status: " .$result->status;
                // echo " MessageId: " .$result->messageId;
                // echo " Cost: "   .$result->cost."\n";
            }
            return $response[0];
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            $response[]=[
                                'status'=>"Encountered an error while sending: ".$e->getMessage()
                        ];
            return $response;
            }
    }
    public function sendActivationSMS($user)
    {
            // Specify your login credentials
            $username   = $this->username;
            $apikey     = $this->apiKey;
            // NOTE: If connecting to the sandbox, please use your sandbox login credentials
            // Specify the numbers that you want to send to in a comma-separated list
            // Please ensure you include the country code (+256 for Uganda in this case)
            $recipients = $user->phone_number;
            // And of course we want our recipients to know what we really do
            $message    = 'Your account has been activated. You can now log in with the details you created';
            // Create a new instance of our awesome gateway class
            $gateway    = new AfricasTalkingGateway($username, $apikey);
            // NOTE: If connecting to the sandbox, please add the sandbox flag to the constructor:
            /*************************************************************************************
                        ****SANDBOX****
            $gateway    = new AfricasTalkingGateway($username, $apiKey, "sandbox");
            **************************************************************************************/
            // Any gateway error will be captured by our custom Exception class below, 
            // so wrap the call in a try-catch block
            try 
            { 
            // Thats it, hit send and we'll take care of the rest. 
            $results = $gateway->sendMessage($recipients, $message);
            $response = [];
            foreach($results as $result) {
                $response[]=[
                                'status'=>$result->status
                            ];
                // // status is either "Success" or "error message"
                // echo " Number: " .$result->number;
                // echo " Status: " .$result->status;
                // echo " MessageId: " .$result->messageId;
                // echo " Cost: "   .$result->cost."\n";
            }
            return $response[0];
            }
            catch ( AfricasTalkingGatewayException $e )
            {
            $response[]=[
                                'status'=>"Encountered an error while sending: ".$e->getMessage()
                        ];
            return $response;
            }
    }
    
    public function verifySMSCode($code)
    {
        $verification = $this->verificationRepo->getVerificationBySMS($code);

        if ($verification === null) {
            return null;
        }

        $verification->verified = true;

        $verification->save();
        $this->verificationRepo->deleteActivation($code);
        return $verification->phone_number;
    }

    public function verifyEmail($code)
    {
    	$verification = $this->verificationRepo->getVerificationByEmail($code);

        if ($verification === null) {
            return null;
        }
        $this->verificationRepo->verifyEmail($code);
        $this->verificationRepo->deleteEmailActivation($verification->email_address);
        return $verification->email_address;
    }

    private function shouldSendSMS($phone_number)
    {
        $verification = $this->verificationRepo->checkVerificationBySMS($phone_number);
    }

    private function shouldSendEmail($email_address)
    {
        $verification = $this->verificationRepo->checkVerificationByEmail($email_address);
    }

}