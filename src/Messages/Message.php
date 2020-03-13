<?php


namespace Calchen\LaravelAliyunSms\Messages;


abstract class Message
{
    // 消息体
    protected $message = [];

    /**
     * @return array
     */
    public function getMessage(): array
    {
        return $this->message;
    }
}