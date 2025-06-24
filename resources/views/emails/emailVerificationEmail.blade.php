<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <h1>Welcome</h1>
      
    <p>Thank you for signing up on our payment platform. To complete the registration process, please verify your email by clicking the link below:</p>
    <a href="{{ route('user.verify', $token) }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 4px;">Verify Email</a>
    <p>If you did not sign up for this account, please ignore this email.</p>
    <p>Best regards,</p>
    <p>Zambia Airports</p>
</body>
</html>
