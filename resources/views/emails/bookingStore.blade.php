<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>

<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #333;">New Order Notification</h2>
        <h3>{{$messageContent}}</h3> 
        <p style="font-size: 16px;">{{$nameContent}} has booked a ticket. Please verify and process the order accordingly.</p>
        <p style="font-size: 16px;">Thank you!</p>
    </div>
    
</body>

</html>