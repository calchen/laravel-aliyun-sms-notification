<?php

namespace Calchen\LaravelAliyunSms\Messages;

use Calchen\LaravelAliyunSms\Exceptions\ErrorCodes;
use Calchen\LaravelAliyunSms\Exceptions\Exception;
use Calchen\LaravelAliyunSms\SmsUser;
use Illuminate\Support\Collection;

class SmsBatchMessage extends Message
{
    const ACTION = 'SendBatchSms';

    /**
     * SmsMessage constructor.
     *
     * @param SmsUser[]|Collection|null $smsUsers
     * @param string|null               $templateCode
     *
     * @throws Exception
     */
    public function __construct($smsUsers = [], string $templateCode = null)
    {
        if (! is_null($smsUsers) && ! is_null($templateCode)) {
            $this->setMessage($smsUsers, $templateCode);
        }
    }

    /**
     * @param SmsUser[]|Collection $smsUsers
     * @param string|null          $templateCode
     *
     * @return $this
     * @throws Exception
     */
    public function setMessage($smsUsers, string $templateCode): self
    {
        if (is_array($smsUsers)) {
            $smsUsers = collect($smsUsers);
        }

        if (! $smsUsers instanceof Collection) {
            throw new Exception(
                __('aliyun_sms::errors.argument_sms_users_type_invalid'),
                ErrorCodes::ARGUMENT_SMS_USERS_TYPE_INVALID
            );
        }

        $this->message = [
            'PhoneNumberJson' => $smsUsers->map(function (SmsUser $v) {
                return $v->fullCellPhoneNumber;
            })->toJson(),
            'SignNameJson'    => $smsUsers->map(function (SmsUser $v) {
                return $v->signName;
            })->toJson(),
            'TemplateCode'    => $templateCode,
        ];
        if (! is_null($smsUsers->templateParams)) {
            $this->message['TemplateParamJson'] = $smsUsers->map(function (SmsUser $v) {
                return $v->templateParams;
            })->toJson();
        }

        return $this;
    }
}