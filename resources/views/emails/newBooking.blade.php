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
    <div style="color:#323338;font-size:13px;font-weight: 300;padding: 30px 0;background-color:#f5f6f8;font-family: 'Poppins', sans-serif;">
		<img style="height: 38px; display: block; border-radius: 8px 8px 0 0; margin: 30px auto 15px;" src="http://booking.theuniformshoppe.co.nz/static/media/logo.3e83e5e41405e2ffb52d.png" alt="TUS Booking">
		<div style="display: block;width: calc(80% - 32px);max-width: 700px;padding: 32px;margin: 0 auto;background-color: #ffffff;border-radius: 0 0 8px 8px;">
			<div style="width: 100%; display: inline-block;">
				<div style="width: 100%; display: block; margin-bottom: 30px;">
          <p>Hello,</p>
					<p>You’ve received the new booking.</p>
				</div>
				<div style="width: 100%; display: block; margin-bottom: 10px;">
					<div style="width: 30%; display: inline-block;"><label style="font-weight:500;display: block;">Booking ID:</label></div>
          <div style="width: 65%; display: inline-block;">{{ $booking['booking_id'] }}</div>
				</div>
				<div style="width: 100%; display: block; margin-bottom: 10px;">
					<div style="width: 30%; display: inline-block;"><label style="font-weight:500;display: block;">Booking Date:</label></div>
          <div style="width: 65%; display: inline-block;">{{ $booking['booking_date'] }}</div>
				</div>
        <div style="width: 100%; display: block; margin-bottom: 10px;">
					<div style="width: 30%; display: inline-block;"><label style="font-weight:500;display: block;">Time Slot:</label></div>
          <div style="width: 65%; display: inline-block;">{{ $booking['time_slots'] }}</div>
				</div>
        <div style="width: 100%; display: block; margin-bottom: 10px;">
					<div style="width: 30%; display: inline-block;"><label style="font-weight:500;display: block;">No of Kids:</label></div>
          <div style="width: 65%; display: inline-block;">{{ $booking['no_of_kids'] }}</div>
				</div>
        <div style="width: 100%; display: block; margin-bottom: 10px;">
					<div style="width: 30%; display: inline-block;"><label style="font-weight:500;display: block;">Store Name:</label></div>
          <div style="width: 65%; display: inline-block;">{{ $booking['store_name'] }}</div>
				</div>
				<div style="width: 100%; display: block; margin-bottom: 10px;">
					<div style="width: 30%; display: inline-block;"><label style="font-weight:500;display: block;">Customer Email:</label></div>
          <div style="width: 65%; display: inline-block;">{{ $booking['customer_email'] }}</div>
				</div>

				<div style="width: 100%; display: inline-block; margin-top: 15px;">
		      <div style="color: #939393; font-size: 12px; font-weight: 500; text-align: center; display: block; margin: 55px auto 0;">Mailed by: TUS Online Booking System</div>			
				</div>
				
			</div>
		</div>
	</div>
  </body>
</html>