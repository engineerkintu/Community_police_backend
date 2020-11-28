
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Mail</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
{{-- <script type="text/javascript" src="http://gc.kis.v2.scr.kaspersky-labs.com/080CC19A-7DA3-7D4D-83BD-C6561119B755/main.js" charset="UTF-8"></script></head> --}}

<body>
      <p>Hello <span> <b>{{ $userto->first_name }}</b> </span></p>
      <p> {{ $statement }}</p>
      <table class="table table-bordered">
          <tr>
            <td><b>Appointment with:</b> </td>
            <td>{{ $userfrom->first_name . ' ' . $userfrom->last_name  }}</td>
          </tr>
          <tr>
            <td><b>Date:</b> </td>
            <td>{{ date('d-M-Y', strtotime($appointment->start_time)) }}</td>
          </tr>
          <tr>
            <td><b>Time:</b> </td>
            <td>{{ date('H:i', strtotime($appointment->start_time))}} to {{ date('H:i', strtotime($appointment->end_time))}}</td>
          </tr>
          
      </table>
      {{-- <p> has requested for an appointment with you on <b></b> starting from <b></b> to <b></b>.</p>
      <p><b> </b>  </p> --}}
      {{-- <p @if(isset($appointment['reason'])) @endif ><b>Message Body: </b> {{ $appointment['reason'] }} </p> --}}

      @include('email.footer');
  </body>
 {{-- <h4><strong>Hello {!! $userto->first_name !!} below are the details of the appointment</strong><br/>
                     <b>Service: {!! $appointment->service !!}</b><br/>
                     <b>Medical Center: {!! $appointment->medical_center !!}</b><br/>
                     <b>Doctor: {!! $userfrom->first_name !!} {!! $userfrom->last_name !!}</b><br/>
                     <b>Start Time: {!! $appointment->start_time !!}</b>
                     <b>End Time: {!! $appointment->end_time !!}</b>
                     </h4> --}}
</html>