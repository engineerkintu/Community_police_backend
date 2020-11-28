
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Mail</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
{{-- <script type="text/javascript" src="http://gc.kis.v2.scr.kaspersky-labs.com/080CC19A-7DA3-7D4D-83BD-C6561119B755/main.js" charset="UTF-8"></script></head> --}}

  <body>
    <p>Hello <span> <b>{{ $user->first_name }}</b> </span></p>
    <p>Use the 5-digit token below to access your Chaguzi Archives. Please note that it can only be used once.</p>
    <p style="font-size: 18pt" ><b>{{ $token }}</b></p>
    @include('email.footer');
  </body>
</html>