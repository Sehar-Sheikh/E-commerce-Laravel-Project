<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Email</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    <h1>You have recieved a contact us email</h1>
    <p><b>Name: </b> {{ $mailData['name'] }}</p>
    <p><b>Email: </b> {{ $mailData['email'] }}</p>
    <p><b>Subject: </b> {{ $mailData['subject'] }}</p>

    <p><b>Message: </b> </p>
    <p>{{ $mailData['message'] }}</p>
</body>

</html>
