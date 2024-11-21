<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        body {
            position: relative;
            display: flex;
            justify-content: center;
        }

        div {
            margin: auto;
            stroke: black;
            overflow: visible;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-self: center;
            position: absolute;
            z-index: 1;
            background-color: white;
            padding: 15px;
        }

        #igChallengeWindow {
            width: 100%;
            height: 90vh;
            margin: auto;
            position: relative;
            z-index: 2;
            border: none;
            box-sizing: border-box;
        }

        span {
            font-family: Arial;
            font-size: 1.5em;
        }

        div>p {
            text-align: center;
            width: 350px;
            align-self: center;
            font-family: "Gellix", "Open Sans", Arial, sans-serif;
            line-height: 1.7;
            font-size: 16px;
            color: #2c3e50;
            margin-top: 15px;
        }

        svg {
            animation: SPIN-SVG 1.4s linear infinite;
            width: 4em;
            height: 4em;
            margin: auto;
            transform: translateY(-50%);
        }

        svg circle {
            transform-origin: center;
            animation: SPIN-CIRCLE 1.4s ease-in-out infinite;
            stroke-dasharray: 125.66;
            stroke-dashoffset: 0;
            stroke-linecap: square;
            stroke-width: 5;
        }

        @keyframes SPIN-SVG {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(270deg);
            }
        }

        @keyframes SPIN-CIRCLE {
            0% {
                stroke-dashoffset: 125.66;
            }

            50% {
                stroke-dashoffset: 31.415;
                transform: rotate(135deg);
            }

            100% {
                stroke-dashoffset: 125.66;
                transform: rotate(450deg);
            }
        }
    </style>
</head>


<body style="height:90vh; width:90vw; margin:auto;">
<img src="data:image/png;base64,{{$qrCode}}" alt="QR Code" />
</body>

</html>
