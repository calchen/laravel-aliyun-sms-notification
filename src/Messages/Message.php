<?php


namespace Calchen\LaravelAliyunSms\Messages;


abstract class Message
{
    const ACTION = null;

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