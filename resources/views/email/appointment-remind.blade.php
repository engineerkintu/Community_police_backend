
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Mail</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
{{-- <script type="text/javascript" src="http://gc.kis.v2.scr.kaspersky-labs.com/080CC19A-7DA3-7D4D-83BD-C6561119B755/main.js" charset="UTF-8"></script></head> --}}

  <body>
    <p>Hello <span> <b>{{ $userto->first_name }}</b> </span></p>
      <p>{{ $userfrom->first_name . ' ' . $userfrom->last_name }} reminds you of an appointment with you on <b>{{ date('d-M-Y', strtotime($appointment['start_time'])) }}</b> starting from <b>{{ date('H:i', strtotime($appointment['start_time']))}}</b> to <b>{{ date('H:i', strtotime($appointment['end_time']))}}</b>.</p>
      @include('email.footer');
  </body>
</html>