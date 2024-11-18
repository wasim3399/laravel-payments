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
<script>
    var creatable = {"amount":2,"number":"fgdsfsfdasadfsfdsfad","currency":"EUR","card":{"uuid":"66f6e7df-53e8-4d59-a449-642559baca84","iin":"411111","last4":"1111","scheme":"visa","token":"eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJjYXJkIiwiaWF0IjoxNzMxOTA4OTczLCJhdWQiOiJwcm9kdWN0aW9uIiwiZW5jIjoiNDguUmlhSVpwNTVaLVZUWXNDZzhUUzJhUS5MaGkzZkxPX0NBZ3VKTGdpazU0c1BCNnBrU09IWjNzUU9MZ252eDhLTE1lM0lvQ0siLCJ4cHIiOlsyLDI1XX0.RxN6-su8JfgJ6iQQAH3AVyKutxudm4re6D5Y-I0wkFONVBVe6ydRMPPrfpnmsqLNvQzvGp3ZsvCV0bikI0s4DFcoNGV8YmAEnzC6eDtaL7z1mGruvsQgeO9WseWpdLgciSTEEeXto5ybPiomY-808rxtX9XZ7OS5h3shGUaDiWA1EEHOi9jr55paOlCBhC7h7LNRGkCzdKtpPY_88MBlbFdDfeYd8SL6UMp_IUGijGQAfx59pyiVRb90y3aEboNdyrjf_tIlH6Oj88rzLG__tr2tC3w_zVXT32HV1kt_drK1BKqZL_jRTHNQnvSmiCXE8UZavza-5uoHBtZJ-x29oQ","expires":[2,25],"csc":"present"},"target":"https://webhook.site/cardeye-hpp-callback","id":"DcN9NMANsOtCX30M","browser":{"ip":"182.176.122.182","ipCountry":"PK"}}

    creatable.browser.parent = window.location.href
    creatable.browser.colorDepth = screen.colorDepth
    creatable.browser.resolution = [screen.width, screen.height]
    creatable.browser.java = navigator.javaEnabled()
    creatable.browser.javascript = true
    creatable.browser.timezone = new Date().getTimezoneOffset()
    creatable.browser.locale = navigator.language

    creatable.target = undefined

    var parentURL = undefined
    var target = "https://webhook.site/cardeye-hpp-callback"
    var key = "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJodHRwczovL2FwaS5wYXlmdW5jLmNvbSIsImlhdCI6MTY0ODE1ODgyNSwibmFtZSI6IkFkZCBEZW1hbmQgdGVzdDEiLCJ0eXBlIjoidGVzdCIsInN1YiI6InlrdkJoMDNZIiwiYXVkIjoicHVibGljIiwiZmVhdHVyZXMiOiIiLCJ1cmwiOiJ3d3cuZXhhbXBsZS5jb20iLCJjYXJkIjp7ImFjcXVpcmVyIjoiMGFreEltTy1sMTNjeW9CZlptYnhSMC13X2ZObTBVV29CSm9FNGV0QTlPSFN6ZDFQaEJjODJpMmRXOVpmNHQyQXBERC1tVk5URWVCejluNGl6TUdzeWlKd2NtOTBiMk52YkNJNkltbHVkR1Z5WjJseWJ5SXNJblZ5YkNJNkltaDBkSEJ6T2k4dllYQnBMbkJoZVdaMWJtTXVZMjl0SW4wIiwiY291bnRyeSI6IlNFIiwiZGVzY3JpcHRvciI6IkFkZCBEZW1hbmQgdGVzdDEiLCJlbXYzZCI6Ikp0ZTFSRGEybU1FLUk1N05QN2JZVWsxNjRHdmhIak1ERE9iSEs2SmtWd19JcGdrQVlRM24xdFFJVllJYWgzVVczYkk0ZmF0X2tjeEQyX1BPZ3Jia0hpSXNJblZ5YkNJNkltaDBkSEJ6T2k4dmMyVnlkbWxqWlM1ellXNWtZbTk0TGpOa2MyVmpkWEpsTG1sdkluMHNleUpyWlhraU9pSnVieTFyWlhraUxDSndjbTkwYjJOdmJDSTZJbU5vTTJReElpd2lkWEpzSWpvaWFIUjBjSE02THk5aGNHa3VZMkZ5WkdaMWJtTXVZMjl0TDJOb00yUXhjMmx0SW4xZCIsIm1jYyI6IjEyMzQiLCJtaWQiOiI4MTAzMDAwMTExIiwidXJsIjoiaHR0cHM6Ly9hcGkucGF5ZnVuYy5jb20ifSwiYWdlbnQiOiJhZGRkZW1hbmR0ZXN0In0.An0fdMs-3CPkasJ7hFrHweNgLx6Wuqwy-g2S8f76Tqw"
    var baseUrl = "https://merchant.intergiro.com/v1"
    var idempotency
    var done = false
    authorize()

    async function authorize() {
        if (done == false) {
            var [status, body] = await post(baseUrl+ "/authorization", creatable)
            var iframe = document.getElementById("verification")
            done = done || status == 201
            if (status == 400 && body && body.error == "verification required") {
                creatable.id = body.id || creatable.id
                const form = document.getElementById("challengeForm")
                const creq = document.getElementById("creq")
                form.action = body.content.details.url
                creq.value = btoa(JSON.stringify({
                    threeDSServerTransID: body.content.details.data.threeDSServerTransID,
                    acsTransID: body.content.details.data.acsTransID,
                    messageVersion: body.content.details.data.messageVersion,
                    messageType: body.content.details.data.messageType,
                    challengeWindowSize: body.content.details.data.challengeWindowSize,
                })).replace(/={1,2}$/, '')
                form.submit()
                document.getElementById("spinner").style.display = "none"
            }
            else if (target)
                submit(body)
            else if (parentURL)
                postMessageExternal(parentURL, body)
        }
    }


    async function post(url, body) {
        var response
        response = await fetch(url, {
            method: "POST",
            headers: new Headers(
                {
                    "Authorization": key,
                    "Content-Type": "application/json",
                    "Accept": "application/jwt",
                    "Idempotency-Key": idempotency
                }),
            body: JSON.stringify(body),
        });
        idempotency = response.headers.get("idempotency-key")
        var contentType = response.headers.get("Content-Type")
        return [
            response.status,
            contentType && contentType.startsWith("application/json")
                ? await response.json()
                : await response.text()
        ];
    }

    async function postMessageExternal(parent, body) {
        window.parent.postMessage(
            { destination: "parent", content: { name: "authorization", value: typeof body == "object" ? JSON.stringify(body) : body } },
            parent
        )
    }

    function submit(body) {
        var input = document.getElementById("authorization")
        input.value = typeof body == "string" ? body : JSON.stringify(body)
        var form = document.getElementById("returnForm")
        form.submit()
    }

    window.addEventListener("message", async e => {
        if (e.data.destination == "parent" && e.data.content.name == "card") {
            e.stopImmediatePropagation()
            creatable.card = e.data.content.value
            await authorize()
        }
    })
    window.addEventListener('load', (event) => {
        getTarget()
    });
    function getTarget() {
        var form = document.getElementById("returnForm")
        form.action = target
    }
</script>

<body style="height:90vh; width:90vw; margin:auto;">
<div id="spinner">
    <svg class="spinner" width="44px" height="44px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">
        <circle class="path" fill="none" cx="33" cy="33" r="20"></circle>
    </svg>
    <p>Please wait while we process your payment, it might take a few minutes. Further verification might be
        required.</p>
</div>
<iframe name="igChallengeWindow" id="igChallengeWindow"></iframe>
<form id="challengeForm" method="post" target="igChallengeWindow" class="hidden">
    <input id="creq" name="creq" type="hidden">
</form>
<form method="post" id="returnForm" action="https://webhook.site/cardeye-hpp-callback" target="_self">
    <input type="hidden" id="authorization" name="authorization"/>
</form>
</body>

</html>
