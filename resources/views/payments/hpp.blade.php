<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .payment-form {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; /* Prevent fields from touching the border */
        }

        .payment-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box; /* Ensures proper spacing within inputs */
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-group .input-group {
            display: flex;
            gap: 10px;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        /* Loader styling */
        .loader {
            display: none;
            margin: 20px auto;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Overlay styling */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<div class="overlay" id="overlay">
    <div class="loader"></div>
</div>

<div class="payment-form">
    <h2>Payment Form</h2>
    <form method="post" action="{{route('makePayment')}}" onsubmit="showLoader(event)">
        @csrf
        <div class="form-group">
            <label for="name">Name on Card</label>
            <input type="text" id="name" name="name" placeholder="John Doe" required>
        </div>

        <div class="form-group">
            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card-number" placeholder="1234 5678 9012 3456" required>
        </div>

        <div class="form-group input-group">
            <div>
                <label for="expiry">Expiry</label>
                <input type="text" id="expiry" name="expiry" placeholder="MM/YY" required>
            </div>
            <div>
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">Submit Payment</button>
    </form>
</div>

<script>
    function showLoader(event) {
        // Prevent double submission
        event.preventDefault();
        const overlay = document.getElementById('overlay');
        overlay.style.display = 'flex';

        // Submit the form after showing the loader
        event.target.submit();
    }
</script>
</body>
</html>
