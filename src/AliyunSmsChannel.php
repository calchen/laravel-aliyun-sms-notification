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
            $accessKeyId = config('aliyun_sms.access_key_id');
            $accessKeySecret = config('aliyun_sms.access_key_secret');
            $region = config('aliyun_sms.region');
            $clientName = config('aliyun_sms.access_key_client_name');

            AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)->regionId($region)->name($clientName);

            $options = $message->getMessage();
            $options['RegionId'] = $region;

            $action = constant(get_class($message)."::ACTION");

            (new Rpc)
                ->client($clientName)
                ->scheme('https')
                ->action($action)
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