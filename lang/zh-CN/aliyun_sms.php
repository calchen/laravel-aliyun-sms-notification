<?php

use Calchen\LaravelAliyunSms\Messages\Message;
use Illuminate\Support\Collection;

return [
    'message_type_error'              => 'Notification 返回的 message 类型必须是 '.Message::class,
    'aliyun_sdk_client_exception'     => '阿里云 SDK 抛出客户端异常。code: :code; message: :message',
    'aliyun_sdk_server_exception'     => '阿里云 SDK 抛出服务端异常。code: :code; message: :message',
    'argument_sms_users_type_invalid' => '入参 $smsUsers 只能是数组或 '.Collection::class,
];