<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

            <p>To reset your password, please use this token below:</p>

            <div class="token-container" style="
                background: #f5f5f5;
                padding: 15px;
                border-radius: 4px;
                margin: 20px 0;
                text-align: center;
            ">
                <p style="
                    font-size: 24px;
                    letter-spacing: 2px;
                    font-weight: bold;
                    color: #333;
                    margin: 0;
                    padding: 10px;
                    font-family: monospace;
                ">{{ $mailData['token'] }}</p>
            </div>

            <p>
                <a href="{{ route($mailData['route']) }}" target="_blank" class="btn">
                    🔑 Reset My Password
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

