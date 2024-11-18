<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #2d572c;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 400px;
        }
        .success-icon {
            font-size: 50px;
            color: #4CAF50;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            margin: 5px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="success-icon">✅</div>
    <h1>Payment Approved!</h1>
    <p><strong>Reference:</strong> {{ $data['reference'] }}</p>
    <p><strong>Amount:</strong> €{{ $data['amount'] }}</p>
    <p><strong>Created At:</strong> {{ \Carbon\Carbon::parse($data['created'])->toDayDateTimeString() }}</p>
    <a href="/" class="btn">Go to Homepage</a>
</div>
</body>
</html>
