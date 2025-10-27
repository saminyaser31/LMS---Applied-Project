<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        /* Reset some default email styling */
        body, table, td, a {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        /* Styling for the main email container */
        .email-wrapper {
            width: 100%;
            background-color: #f4f7fc;
            padding: 20px;
            text-align: center;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            color: #4CAF50;
            margin-bottom: 20px;
            font-weight: bold;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #555;
        }

        /* The clickable button style */
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .btn:hover {
            background-color: #45a049;
            transition: background-color 0.3s ease;
        }

        .footer {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .emoji {
            font-size: 40px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="email-wrapper">
    <div class="email-container">
        <h1>✨ Almost There, [{{ $mailData['first_name'] }}]! ✨</h1>

        <p>We’re thrilled to know that you want to join us! To get started, we just need you to <strong>verify your email</strong> so you can unlock all the amazing features waiting for you.</p>
        <p>Just click the link below to verify your email, and you’ll be all set to dive in:</p>

        <p>
            <a href="{{ route($mailData['route'], $mailData['token']) }}" target="_blank" class="btn">
                🔑 Verify My Email
            </a>
        </p>

        <p class="footer">
            If you didn’t sign up for this, no worries! You can simply ignore this message.
            <br>Or if you have any issues, reach out to us at <a href="mailto:{{ $mailData['email'] }}">{{ $mailData['email'] }}</a>.
        </p>
    </div>
</div>

</body>
</html>

