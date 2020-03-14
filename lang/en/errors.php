<?php

use Calchen\LaravelAliyunSms\Messages\Message;
use Illuminate\Support\Collection;

return [
    'message_type_error'              => 'The notification show return message instance of '.Message::class,
    'aliyun_sdk_client_exception'     => 'Aliyun SDK client exception. code: :code; message: :message',
    'aliyun_sdk_server_exception'     => 'Aliyun SDK server exception. code: :code; message: :message',
    'aliyun_api_failed'               => 'API request failed. code: :code; message: :message',
    'argument_sms_users_type_invalid' => 'Input argument $smsUsers can only be array or '.Collection::class,
];