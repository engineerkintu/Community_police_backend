
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Account activated</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <body>
    
      <p>Hello <span> <b>{{ $user->first_name }}</b> </span></p>
      <p>Your account has been successfully activated. You may now log in</p>
      @if ($code)
      <p>Your store code is {{ $code }}, please keep it safe</p>
      @endif
      <p>Enjoy 6 Months of premium service!</p>
      @include('email.footer');

  </body>

</html>