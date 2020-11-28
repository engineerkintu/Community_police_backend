
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>New user</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <body>
    
      <p>Hello <span> <b>Admin</b> </span></p>
      <p>A new user has registered on the app, the details are as below</p>
      <p><b>Account Type: </b> {!! $account_type->name !!} </p>
      <p><b>Name: </b> {!! $user->first_name !!} {!! $user->last_name !!} </p>
      <p><b>Phone Number: </b> {!! $user->phone_number !!} </p>
      <p><b>Email Address: </b> {!! $user->email_address !!} </p>
      <p><b>Country: </b> {!! $user->mycountry->name !!} </p>
      
      @if ($pharmacy)
          <p><b>Pharmacy: </b> {!! $pharmacy->name !!} </p>
          <p><b>Pharmacy Email: </b> {!! $pharmacy->email !!} </p>
          <p><b>Pharmacy Phone: </b> {!! $pharmacy->phone !!} </p>
          <p><b>Pharmacy Address: </b> {!! $pharmacy->address !!} </p>
      @endif

      @if($account_type->id == 4)
        @if ($specialist)
            <p><b>Doctor's Registered Name: </b> {!! $specialist->surname !!} {!! $specialist->other_name !!} </p>
            <p><b>Doctor's Registration Number: </b> {!! $specialist->reg_no !!} </p>
            <p><b>Doctor's Registered Phone: </b> {!! $specialist->telephone !!} </p>
        @else
            <p><b>Note that no record of this doctor has been found in the doctor's directory</b></p>
        @endif
      @endif
      @if($account_type->id == 6)
        @if ($medicalcenter)
            <p><b>Medical Center: </b> {!! $medicalcenter->name !!} </p>
            <p><b>Medical Center Phone Number: </b> {!! $medicalcenter->phone_number !!} </p>
            <p><b>Medical Center Email Address: </b> {!! $medicalcenter->email_address !!} </p>
            <p><b>Medical Center Address: </b> {!! $medicalcenter->address !!} </p>
        @else
            <p><b>Note that user has not availed details of their medical center</b></p>
        @endif
      @endif
      
      @if ($pharmacy)
        <p><strong>To activate account (With POS system)</strong> : <a target="_blank" href="{{ $user->link_code }}">Click here</a> </p>
        <p><strong>To activate account (Without POS system)</strong> : <a target="_blank" href="{{ $user->link }}">Click here</a> </p>
      @else
        <p><strong>Activate Account</strong> : <a target="_blank" href="{{ $user->link }}">Click here</a> </p>
      @endif

      @include('email.footer');
  </body>

</html>