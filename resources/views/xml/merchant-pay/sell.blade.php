<?xml version="1.0" encoding="UTF-8"?>
<payment_transaction>
    <transaction_type>{{ $transaction_type }}</transaction_type>
    <transaction_id>{{ $transaction_id }}</transaction_id>
    <usage>{{ $usage }}</usage>
    <remote_ip>{{ $remote_ip }}</remote_ip>
    <amount>{{ $amount }}</amount>
    <currency>{{ $currency }}</currency>
    <card_holder>{{ $card_holder }}</card_holder>
    <card_number>{{ $card_number }}</card_number>
    <expiration_month>{{ $expiration_month }}</expiration_month>
    <expiration_year>{{ $expiration_year }}</expiration_year>
    <cvv>{{ $cvv }}</cvv>
    <customer_email>{{ $customer_email }}</customer_email>
    <customer_phone>{{ $customer_phone }}</customer_phone>
    <billing_address>
        <first_name>{{ $billing_address['first_name'] }}</first_name>
        <last_name>{{ $billing_address['last_name'] }}</last_name>
        <address1>{{ $billing_address['address1'] }}</address1>
        <zip_code>{{ $billing_address['zip_code'] }}</zip_code>
        <city>{{ $billing_address['city'] }}</city>
        <neighborhood>{{ $billing_address['neighborhood'] }}</neighborhood>
        <country>{{ $billing_address['country'] }}</country>
    </billing_address>
    <notification_url>{{ $notification_url }}</notification_url>
    <return_success_url>{{ $return_success_url }}</return_success_url>
    <return_failure_url>{{ $return_failure_url }}</return_failure_url>
    <threeds_v2_params>
        <threeds_method>
            <callback_url>https://webhook.site/cardeye-hpp-callback</callback_url>
        </threeds_method>
        <control>
            <device_type>browser</device_type>
            <challenge_window_size>full_screen</challenge_window_size>
        </control>
        <browser>
            <accept_header>*/*</accept_header>
            <java_enabled>false</java_enabled>
            <language>en-US</language>
            <color_depth>24</color_depth>
            <screen_height>720</screen_height>
            <screen_width>1280</screen_width>
            <time_zone_offset>0</time_zone_offset>
            <user_agent>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.6668.29 Safari/537.36</user_agent>
        </browser>
    </threeds_v2_params>
</payment_transaction>
