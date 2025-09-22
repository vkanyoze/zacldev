<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .code-container {
            background: white;
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 Two-Factor Authentication</h1>
        <p>Zambia Airports Corporation Limited</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }},</h2>
        
        <p>You have requested a two-factor authentication code for your ZACL account. Use the code below to complete your login:</p>
        
        <div class="code-container">
            <p style="margin: 0 0 10px 0; color: #666;">Your verification code is:</p>
            <div class="code">{{ $code }}</div>
            <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">This code expires in 10 minutes</p>
            @if(app()->environment('local'))
            <p style="margin: 10px 0 0 0; color: #28a745; font-size: 12px; font-weight: bold;">
                🧪 Development Mode - This email was sent via Mailtrap
            </p>
            @endif
        </div>
        
        <div class="warning">
            <strong>⚠️ Security Notice:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Never share this code with anyone</li>
                <li>ZACL will never ask for your verification code</li>
                <li>If you didn't request this code, please ignore this email</li>
            </ul>
        </div>
        
        <p>If you're having trouble logging in, please contact our support team.</p>
        
        <p>Thank you for using ZACL's secure payment system.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from Zambia Airports Corporation Limited</p>
        <p>© {{ date('Y') }} ZACL. All rights reserved.</p>
    </div>
</body>
</html>
