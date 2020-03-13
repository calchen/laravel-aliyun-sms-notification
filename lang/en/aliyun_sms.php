<?php

use Calchen\LaravelAliyunSms\Messages\Message;

return [
    'message_type_error' =>'The notification show return message instance of '.Message::class,
    'aliyun_sdk_client_exception' => 'Aliyun SDK client exception. code: :code; message: :message',
    'aliyun_sdk_server_exception' => 'Aliyun SDK server exception. code: :code; message: :message',
];