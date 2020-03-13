<?php

namespace Calchen\LaravelAliyunSms;

use Calchen\LaravelAliyunSms\Messages\Message;

interface AliyunSmsNotification
{
    public function toAliyunSms($notifiable): Message;
}