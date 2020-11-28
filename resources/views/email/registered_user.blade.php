
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>New user</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <body>
    
      <p>Hello <span> <b>{{ $user->first_name }}</b> </span></p>

      <p>Congratulations! You have successfully registered with Chaguzi</p>
      <p>You will receive a notification once your account has been activated, after which you will be able to log in</p>
      <p>Enjoy 6 Months of premium service!</p>
      @include('email.footer');
  </body>

</html>