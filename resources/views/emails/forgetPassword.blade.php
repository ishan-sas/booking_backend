<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500&display=swap" rel="stylesheet">

  <title>TUS Booking</title>
</head>

<body style="margin: 0px; padding: 0px;">
  <div
    style="color:#323338;font-size:13px;font-weight: 300;padding: 30px 0;background-color:#f5f6f8;font-family: 'Poppins', sans-serif;">
    <img style="height: 38px; display: block; border-radius: 8px 8px 0 0; margin: 30px auto 15px;"
      src="http://booking.theuniformshoppe.co.nz/static/media/logo.3e83e5e41405e2ffb52d.png" alt="TUS Booking">
    <div
      style="display: block;width: calc(80% - 32px);max-width: 700px;padding: 32px;margin: 0 auto;background-color: #ffffff;border-radius: 0 0 8px 8px;">
      <div style="width: 100%; display: inline-block;">
        <div style="width: 100%; display: block; margin-bottom: 30px;">
          <p>Dear valued customer,</p>
          <br />
          <p>We received a request to reset your password. Please click the link below to set a new password.</p>
          <a href="http://booking.theuniformshoppe.co.nz/#/reset-password/{{ $booking['token'] }}">Reset Link</a>
          <p>If you did not request a password reset, please ignore this email.</p>
        </div>
        
        <div style="width: 100%; display: inline-block; margin-top: 15px;">
          <div
            style="color: #939393; font-size: 12px; font-weight: 500; text-align: center; display: block; margin: 55px auto 0;">
            Mailed by: TUS Online Booking System</div>
        </div>

      </div>
    </div>
  </div>
</body>

</html>