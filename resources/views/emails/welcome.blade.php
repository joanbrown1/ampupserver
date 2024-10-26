<!-- resources/views/emails/welcome.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Congratulations, you have been given {{ $admin->privilage }} rights</title>
</head>
<body>
    <h1>Hello, {{ $admin->email }}!</h1>
    <br/><p>Password: admin12345</p>
    <br/><br/><p>Best regards,<br>PowerKiosk Team</p>
</body>
</html>
