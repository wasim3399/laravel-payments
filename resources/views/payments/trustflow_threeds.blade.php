<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" href="https://sandbox.trustflowpay.com/pgui/checkoutlibrary/checkout.min.css">
    <script src="https://sandbox.trustflowpay.com/pgui/checkoutlibrary/checkout.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>S2S Form POST</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <style type="text/css">
        body {
            width: 100%;
            margin: 0 auto;
            /* background-color: #f7f9fd */
        }

        .demo-page_wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            font-family: 'Titillium Web', sans-serif
        }

        .demo-page_container {
            width: 100%;
            max-width: 767px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, .1);
            border-radius: 10px
        }

        .dm-container {
            padding: 0 15px
        }

        .demo-page_header {
            text-align: center;
            position: relative
        }

        .demo-page_header h4 {
            margin: 0;
            padding-top: 20px;
            margin-bottom: 20px;
            font-size: 20px;
            display: inline-block;
            background-color: #fff;
            padding-left: 20px;
            padding-right: 20px;
            position: relative;
            z-index: 999
        }

        .demo-page_header:after {
            content: "";
            width: 100%;
            height: 2px;
            position: absolute;
            top: 30px;
            left: 0;
            background-color: #00589f
        }

        .dm-row {
            margin: 0 -15px;
            display: flex;
            flex-wrap: wrap
        }

        .dm-input_group {
            width: 100%;
            /* max-width: 33.33%; */
            padding: 0 15px;
            box-sizing: border-box;
            margin-bottom: 20px
        }

        .dm-input_group label {
            font-size: 12px;
            margin-bottom: 5px;
            display: block
        }

        .dm-input_group .dm-input_control {
            width: 100%;
            height: 30px;
            border: 1px solid #ddd;
            text-indent: 10px;
            border-radius: 5px
        }

        .dm-button-wrapper {
            text-align: center;
            width: 100%;
            margin-bottom: 20px
        }

        .dm-button-wrapper .dm-button {
            background-color: #00589f;
            color: #fff;
            padding: 7px 15px;
            display: inline-block;
            border: none;
            border-radius: 5px;
            cursor: pointer
        }

        .iframe-div-cardeye-loader {
            width: 100% !important;
            max-width: 100% !important;
            word-wrap: break-word !important;
            height: 100%;
            position: fixed;
            z-index: 9999;
            background: no-repeat center center rgba(0, 0, 0, 0.25);
            background-size: 75px;
            background-color: #595959;
            /* opacity: 70%; */
        }

        .display-text {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 107vh;
            margin-top: 50px;
            color: white;
            text-align: center;
        }

        .cl_main_div {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh !important;
        }

        .timer_btn_style {
            color: white !important;
            border: none;
            width: 300px;
            box-shadow: 0px 3px 12px #F1F1F5;
            background: linear-gradient(91.7deg, #ED8F91 5.63%, #FFA894 85.82%);
            margin-top: 40px;
        }
    </style>
</head>

<body>
<div class="iframe-div-cardeye-loader" style="background-image: url('{{ asset('image/cardeye-loader.gif') }}');">
    <span class="display-text">Please wait while your transaction is being processing...!</span>
</div>
<div class="demo-page_wrapper cl_main_div">
    <div class="demo-page_container" style="display: none">
        <div class="demo-page_header">
            <h5>Please wait, Do not close or refresh the page, Waiting for 3DS Verfication </h5>
        </div>
        <form id="myForm3DSPage" target="checkout-iframe" onsubmit="return checkoutSubmitHandler(this)" name="autoPost" action="https://sandbox.trustflowpay.com/pgui/jsp/capturePayment"
              method="post">
            <div class="demo-page_formElement">
                <div class="dm-container">
                    <div class="dm-row">
                        <div class="dm-input_group"><label for="">APP ID:</label>
                            <input type="text" name="APP_ID" class="dm-input_control"
                                   value="{{$app_id}}" autocomplete="off">
                        </div>
                        <div class="dm-input_group"><label for="">TXN ID:</label>
                            <input type="text" id="TXN_ID" name="TXN_ID" class="dm-input_control"
                                   value="{{$trx_id}}" autocomplete="off"
                                   placeholder="Enter TXN_ID of transaction">
                        </div>
                        <div class="dm-input_group"><label for="">HASH:</label>
                            <input type="text" id="HASH" name="HASH" class="dm-input_control"
                                   value="{{$hash}}" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    document.autoPost.submit();

    var appUrl = "http://127.0.0.1:8000";
    // setTimeout(chkRendixTranxStatus, 5000);

    function chkRendixTranxStatus() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            type: 'GET',
            url: appUrl + '/api/trust-flow-pay-transaction-status/1122241122161911',

            error: function(jqXHR, textStatus, errorThrown) {
                hideShowLoader('hide');
            },
            success: function(data, textStatus, xhr) {
                if (data.status == "ok" || data.status == "failed") {
                    hideShowLoader('hide');
                    $(".cl_main_div").empty();
                    $(".cl_main_div").html(data.html);
                } else {
                    setTimeout(chkRendixTranxStatus, 5000);
                }
            },
        });
    }

    function hideShowLoader(type) {
        if (type == "show") {
            $(".iframe-div-cardeye-loader").show();
            $(".display-text").show();
        } else {
            $(".iframe-div-cardeye-loader").hide();
            $(".display-text").hide();
        }
    }
</script>
</body>

</html>
