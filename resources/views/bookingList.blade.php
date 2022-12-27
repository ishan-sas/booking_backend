<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF</title>
    <style>
      th, td {
        border: 1px solid black;
      }
    </style>
  </head>
  <body>
    <table class="table table-bordered">
    <Table>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Contact No</th>
        <th>Date</th>
        <th>Time Slot</th>
        <th>Kids</th>
      </tr>
      @foreach ($bookings as $booking)
      <tr>
        <td>#{{$booking['id']}}</td>
        <td>{{$booking['name']}}</td>
        <td>{{$booking['email']}}</td>
        <td>{{$booking['contact_no']}}</td>
        <td>{{$booking['booking_date']}}</td>
        <td>
        @foreach ($booking['slots'] as $value)
          {{ $value }}, 
        @endforeach
        </td>
        <td>{{$booking['no_of_kids']}}</td>
      </tr>
      @endforeach
    </Table>
  </body>
</html>