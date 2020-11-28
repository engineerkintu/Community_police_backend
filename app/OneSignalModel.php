<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use OneSignal;
use App\User;
use Carbon\Carbon;

class OneSignalModel extends Model
{
    private $onesignal_id = "f7f52013-d0ad-46a1-8b18-d3619465936d";
    private $onesignal_restapi_id = "MGRkNGJlMjMtYjhiYi00NmNjLTlmZjItOGQzNzFiM2I5MDE2";

    public function getReceipientPlayerId($appointment)
    {

        $player_id = null;

        if ($appointment->account_type == '1') {
            // if its the patient making appointment, then receipient is doctor (doctor_id is the key)
            $receipient_id = $appointment->doctor_id;
        } else {
            // else its the patient that is to be notified
            $receipient_id = $appointment->patient_id;
        }

        // extract their token db object
        $obj = DB::table('device_token_manager')->where('chaguzi_id', $receipient_id)->orderBy('created_at', 'desc')->first();

        if ($obj) {
            $player_id = $obj->user_id;
        }

        return $player_id;
    }

    public function format_date($date)
    {
        return date('d-M-Y', strtotime($date));
    }
    public function format_time($date)
    {
        return date('H:i', strtotime($date));
    }

    public function getSource($appointment)
    {

        if ($appointment->account_type == '1') {
            // doctor is receipient
            return User::find($appointment->patient_id);
        } else {
            // otherwise its the patient
            return User::find($appointment->doctor_id);
        }
    }


    public function sendNewAppointmentNotificationv2($appointment, $status = "new")
    {
        /**
         * $status can either be a new, reminder or a canceled appointment
         */

        $player_id = $this->getReceipientPlayerId($appointment);

        if ($player_id) {

            $receipient =  $this->getSource($appointment);
            $date = $this->format_date($appointment->start_time);
            $start_time = $this->format_time($appointment->start_time);
            $end_time = $this->format_time($appointment->end_time);
            $name = $receipient->fullname();
            if ($status == 'new') {
                $title = "Appointment Request";
                $body = $name . " requests for an appointment with you on $date starting from $start_time to $end_time. You may view the details in the app";
            }
            if ($status == 'reminder') {
                $title = "Reminder";
                $body = "This reminds you of an appointment with $name on $date starting from $start_time to $end_time. You may view the details in the app";
            }
            if ($status == 'canceled') {
                $title = "Appointment Canceled";
                $body = $name . " has canceled their appointment request with you. No action is required";
            }
            if ($status == 'approved') {
                $title = "Appointment accepted";
                $body = $name . " has accepted your appointment request";
            }

            $params = array(
                "action" => 1,
                "time" => $start_time
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => ["en" => $title],
                'include_player_ids' => [$player_id],
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => ["en" => $body]
            );

            $fields = json_encode($fields);

            return $this->make_curl_call($fields);
        }
    }
    public function sendAppointmentReminder($appointment)
    {
        $doctor = $appointment->doctor_id;
        $patient = $appointment->patient_id;

        $doctor_player_id = $this->getUserId($doctor);
        $patient_player_id = $this->getUserId($patient);

        $date = $this->format_date($appointment->start_time);
        $start_time = $this->format_time($appointment->start_time);
        $end_time = $this->format_time($appointment->end_time);

        if ($doctor_player_id) {

            $receipient =  User::find($patient);
            $name = $receipient->fullname();
           
            $title = "Reminder";
            $body = "This reminds you of an appointment with $name on $date starting from $start_time to $end_time. You may view the details in the app";
            
            $params = array(
                "action" => 1,
                "time" => $start_time
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => ["en" => $title],
                'include_player_ids' => [$doctor_player_id],
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => ["en" => $body]
            );

            $fields = json_encode($fields);

            $this->make_curl_call($fields);
            
        }
        if ($patient_player_id) {

            $receipient =  User::find($doctor);
            $name = $receipient->fullname();
           
            $title = "Reminder";
            $body = "This reminds you of an appointment with $name on $date starting from $start_time to $end_time. You may view the details in the app";
            
            $params = array(
                "action" => 1,
                "time" => $start_time
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => ["en" => $title],
                'include_player_ids' => [$patient_player_id],
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => ["en" => $body]
            );

            $fields = json_encode($fields);

            $this->make_curl_call($fields);
            
        }
    }

    public function make_curl_call($fields){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                "Authorization: Basic $this->onesignal_restapi_id"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public function sendNewAppointmentNotification($admins, $data, $flag = false)
    {
        $admins = $this->getUSerIds($admins);
        if (count($admins) > 0) {

            $date = $this->format_date($data->start_time);
            $start_time = $this->format_time($data->start_time);
            $end_time = $this->format_time($data->end_time);
           
            if($flag){
                $content = array(
                    "en" => "Appointment has been successfully assigned!. You may view the details in the app"
                );
            }else{
                $content = array(
                    "en" => $data->patient . " requests for an appointment with you on $date starting from $start_time to $end_time. You may view the details in the app"
                );
            }
            

            $headings = array(
                "en" => "Appointment Request"
            );

            $params = array(
                "action" => 1,
                "time" => $start_time
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => $headings,
                'include_player_ids' => $admins,
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => $content
            );

            $fields = json_encode($fields);

            $this->make_curl_call($fields);

            return 1;
        }
        return 2;
    }

    public function acceptChatRequestPush($data, $status = "accepted"){

        $user_i = DB::table('client_request')->where('id', $data['request_id'])->value('user_id');
        $client_id = DB::table('client_request')->where('id', $data['request_id'])->value('client_id');
        $client = User::find($client_id);
        $user_id = $this->getUserId($user_i);

        if ($user_id) {
            $content = array(
                "en" => $client->fullname() . " has $status to chat. Go to app to chat"
            );

            $headings = array(
                "en" => 'Chat Request Accepted'
            );

            $params = array(
                "action"    => 2,
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => $headings,
                'include_player_ids' => array($user_id),
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => $content
            );

            $fields = json_encode($fields);

            $this->make_curl_call($fields);

            return 1;
        }
        return 2;


    }
    public function sendPushNotificationForClientRequest($data){
        $send_from = User::find($data['user_id']);
        $send_to = User::find($data['client_id']);
        $user_id = $this->getUserId($data['client_id']);
        if ($user_id) {
            $content = array(
                "en" => $send_from->fullname() . ' would like to chat with you. Go to app to accept the chat request'
            );

            $headings = array(
                "en" => 'Chat Request'
            );

            $params = array(
                "action"    => 2,
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => $headings,
                'include_player_ids' => array($user_id),
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => $content
            );

            $fields = json_encode($fields);

            $this->make_curl_call($fields);

            return 1;
        }
        return 2;
    }
    public function sendChatNotification($data)
    {
        $user_id = $this->getUSerId($data['receiver_id']);
        if ($user_id) {
            $content = array(
                "en" => $data['message']
            );

            $headings = array(
                "en" => $data['name']
            );

            $params = array(
                "action"    => 2,
            );

            $fields = array(
                'app_id' => $this->onesignal_id,
                'headings' => $headings,
                'include_player_ids' => array($user_id),
                'data' => $params,
                'large_icon' => "ic_launcher_round.png",
                'contents' => $content
            );

            $fields = json_encode($fields);

            $this->make_curl_call($fields);

            return 1;
        }
        return 2;
    }

    private function getUserId($id)
    {
        $user = DB::table('device_token_manager')->where('chaguzi_id', $id)->orderBy('created_at', 'desc')->first();
        if ($user)
            return $user->user_id;
    }

    private function getUSerIds($data)
    {
        $ids = [];
        foreach ($data as $r) {
            $user = DB::table('device_token_manager')->where('chaguzi_id', $r->user_id)->orderBy('created_at', 'desc')->first();
            if ($user)
                $ids[] = $user->user_id;
        }

        return $ids;
    }
}
