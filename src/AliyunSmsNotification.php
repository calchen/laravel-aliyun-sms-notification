<?php

namespace Calchen\LaravelAliyunSms;

interface AliyunSmsNotification
{
    public function toAliyunSms($notifiable);
}