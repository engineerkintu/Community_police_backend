<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <h2>Password Reset</h2>

    <div>
      Click here to reset your password:
      {{ url('v1/password/reset/'.$token) }}
    </div>
  </body>
</html>