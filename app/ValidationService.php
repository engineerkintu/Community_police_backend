<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Config;

class ValidationService extends Model
{
	// ensure the phone number is requires, startes with 256 and is followed by 
	// six numeric characters between 0 and 9     
    public function isValid($data,$type){
        switch ($type) {
            case 'change_password':
                $validation = Validator::make($data, Config::get('validation.change_password_rules'),Config::get('validation.change_password_messages'));
                break;
            case 'add_complaint':
                $validation = Validator::make($data,Config::get('validation.add_complaint_rules'),Config::get('validation.add_complaint_messages'));
                break;
            case 'add_crime':
                $validation = Validator::make($data,Config::get('validation.add_crime_rules'),Config::get('validation.add_crime_messages'));
                break;
            case 'verify_email_code':
                $validation = Validator::make($data,Config::get('validation.verify_email_code_rules'),Config::get('validation.verify_email_code_messages'));
                break;
            case 'verify_email':
                $validation = Validator::make($data,Config::get('validation.validate_email_rules'),Config::get('validation.validate_email_messages'));
                
            
            break;
            case 'register_user':
                $validation = Validator::make($data,Config::get('validation.register_user_rules'),Config::get('validation.register_user_messages'));
                break;
            case 'add_staff_response_complaint':
                $validation = Validator::make($data,Config::get('validation.add_staff_response_complaint_rules'),Config::get('validation.add_staff_response_complaint_messages'));
                break;
            case 'add_staff_response_crime':
                $validation = Validator::make($data,Config::get('validation.add_staff_response_crime_rules'),Config::get('validation.add_staff_response_crime_messages'));
                break;
            case 'get_civilian_complaints':
                $validation = Validator::make($data,Config::get('validation.get_civilian_complaints_rules'),Config::get('validation.get_civilian_complaints_messages'));
                break; 
            case 'get_village_complaints':
                $validation = Validator::make($data,Config::get('validation.get_village_complaints_rules'),Config::get('validation.get_village_complaints_messages'));
                break;
            case 'get_civilian_crimes':
                $validation = Validator::make($data,Config::get('validation.get_civilian_crimes_rules'),Config::get('validation.get_civilian_crimes_messages'));
                break;
            case 'get_village_crimes':
                $validation = Validator::make($data,Config::get('validation.get_village_crimes_rules'),Config::get('validation.get_village_crimes_messages'));
                break;
            case 'get_response_complaint':
                $validation = Validator::make($data,Config::get('validation.get_response_complaint_rules'),Config::get('validation.get_response_complaint_messages'));
                break;
            case 'get_staff_response_complaint':
                $validation = Validator::make($data,Config::get('validation.get_staff_response_complaint_rules'),Config::get('validation.get_staff_response_complaint_messages'));
                break;
            case 'get_crime_response':
                $validation = Validator::make($data,Config::get('validation.get_crime_response_rules'),Config::get('validation.get_crime_response_messages'));
                break;
            case 'get_staff_response_crimes':
                $validation = Validator::make($data,Config::get('validation.get_staff_response_crimes_rules'),Config::get('validation.get_staff_response_crimes_messages'));
                break;
	        default:
	    	    break;
    	}
     	if($validation->passes()) return true;
       	    $this->errors= $validation->messages(); 
      	return false;
    }
}

