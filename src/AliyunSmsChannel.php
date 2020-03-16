<?php

namespace Calchen\LaravelAliyunSms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Dysmsapi\V20170525\Rpc;
use Calchen\LaravelAliyunSms\Exceptions\ErrorCodes;
use Calchen\LaravelAliyunSms\Exceptions\Exception;
use Calchen\LaravelAliyunSms\Exceptions\InvalidArgumentException;
use Calchen\LaravelAliyunSms\Messages\Message;
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
            throw new InvalidArgumentException(
                __('aliyun_sms::errors.message_type_error'),
                ErrorCodes::MESSAGE_TYPE_ERROR
            );
        }

        try {
            $accessKeyId = config('aliyun_sms.access_key_id');
            $accessKeySecret = config('aliyun_sms.access_key_secret');
            $region = config('aliyun_sms.region');
            $host = config('aliyun_sms.host');
            $clientName = config('aliyun_sms.access_key_client_name');

            AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
                ->regionId($region)
                ->name($clientName);

            $options = $message->getMessage();
            $options['RegionId'] = $region;

            $action = constant(get_class($message)."::ACTION");

            $response = (new Rpc)
                ->client($clientName)
                ->scheme('https')
                ->action($action)
                ->host($host)
                ->options([
                    'query' => $options,
                ])
                ->request();

            // Code 为 Ok 时表示发送成功
            // 文档 https://help.aliyun.com/document_detail/101346.html?spm=a2c4g.11186623.6.621.cbd42246K5pp8V
            if ($response->get('Code') != 'OK') {
                throw new Exception(
                    __('aliyun_sms::errors.aliyun_api_failed', [
                        'code'    => $response->get('Code'),
                        'message' => $response->get('Message'),
                    ]),
                    ErrorCodes::ALIYUN_API_FAILED
                );
            }
        } catch (ClientException $e) {
            throw new Exception(
                __('aliyun_sms::errors.aliyun_sdk_client_exception', [
                    'code'    => $e->getErrorCode(),
                    'message' => $e->getErrorMessage(),
                ]),
                ErrorCodes::ALIYUN_SDK_CLIENT_EXCEPTION,
                $e->getPrevious()
            );
        } catch (ServerException $e) {
            throw new Exception(
                __('aliyun_sms::errors.aliyun_sdk_server_exception', [
                    'code'    => $e->getErrorCode(),
                    'message' => $e->getErrorMessage(),
                ]),
                ErrorCodes::ALIYUN_SDK_SERVER_EXCEPTION,
                $e->getPrevious()
            );
        }
    }
}