<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #a94442;
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
        .fail-icon {
            font-size: 50px;
            color: #e53935;
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
            background-color: #e53935;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="fail-icon">❌</div>
    <h1>Payment Failed</h1>
    @if(isset($data))
        <p><strong>Status:</strong> {{ $data['status'] ?? 'Unknown' }}</p>
        <p><strong>Reference:</strong> {{ $data['reference'] ?? 'N/A' }}</p>
    @else
        <p><strong>Error:</strong> {{ $error }}</p>
    @endif
</div>
</body>
</html>
