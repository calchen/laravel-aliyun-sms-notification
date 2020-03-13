<?php

namespace Calchen\LaravelAliyunSms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Dysmsapi\V20170525\Rpc;
use Calchen\LaravelAliyunSms\Exceptions\ErrorCodes;
use Calchen\LaravelAliyunSms\Exceptions\InvalidArgumentException;
use Calchen\LaravelAliyunSms\Messages\Message;
use Exception;
use Illuminate\Notifications\Notification;
use ReflectionException;

class AliyunSmsChannel
{
    /**
     * Send the given notification.
     *
     * @param              $notifiable
     * @param Notification $notification
     *
     * @throws Exception
     * @throws ReflectionException
     */
    public function send($notifiable, Notification $notification)
    {
        /** @var Message $message */
        $message = $notification->toAliyunSms($notifiable);
        if (! $message instanceof Message) {
            throw new InvalidArgumentException(ErrorCodes::MESSAGE_TYPE_ERROR, __('aliyun_sms.message_type_error'));
        }

        try {
            AlibabaCloud::accessKeyClient(config('aliyun_sms.access_key_id'), config('aliyun_sms.access_key_secret'))
                ->regionId(config('aliyun_sms.aliyun.region'))
                ->name(config('aliyun_sms.access_key_client_name'));

            $options = $message->getMessage();
            $options['RegionId'] = config('aliyun_sms.region');

            (new Rpc)
                ->scheme('https')
                ->action('SendSms')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => $options,
                ])
                ->request();
        } catch (ClientException $e) {
            throw new Exception(
                ErrorCodes::ALIYUN_SDK_CLIENT_EXCEPTION,
                __('aliyun_sms.aliyun_sdk_client_exception', [
                    'code'    => $e->getErrorCode(),
                    'message' => $e->getErrorMessage(),
                ]),
                $e->getPrevious()
            );
        } catch (ServerException $e) {
            throw new Exception(
                ErrorCodes::ALIYUN_SDK_SERVER_EXCEPTION,
                __('aliyun_sms.aliyun_sdk_server_exception', [
                    'code'    => $e->getErrorCode(),
                    'message' => $e->getErrorMessage(),
                ]),
                $e->getPrevious()
            );
        }
    }
}