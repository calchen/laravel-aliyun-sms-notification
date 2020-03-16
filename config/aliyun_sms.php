<?php

return [
    'access_key_id'          => env('ALIYUN_ACCESS_KEY_ID'),
    'access_key_secret'      => env('ALIYUN_ACCESS_KEY_SECRET'),
    'region'                 => env('ALIYUN_SMS_REGION'),
    'host'                   => env('ALIYUN_SMS_HOST', 'dysmsapi.aliyuncs.com'),
    'access_key_client_name' => env('ALIYUN_SMS_ACCESS_KEY_CLIENT_NAME', 'sms'),
];
