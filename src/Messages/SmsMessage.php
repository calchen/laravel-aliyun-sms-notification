<?php

namespace Calchen\LaravelAliyunSms\Messages;

use Calchen\LaravelAliyunSms\SmsUser;

class SmsMessage extends Message
{
    /**
     * SmsMessage constructor.
     *
     * @param SmsUser|null $smsUser
     * @param string|null  $templateCode
     */
    public function __construct(SmsUser $smsUser = null, string $templateCode = null)
    {
        if (! is_null($smsUser) && ! is_null($templateCode)) {
            $this->setMessage($smsUser, $templateCode);
        }
    }

    /**
     * @param SmsUser|null $smsUser
     * @param string|null  $templateCode
     *
     * @return $this
     */
    public function setMessage(SmsUser $smsUser, string $templateCode): self
    {
        $this->message = [
            'PhoneNumbers' => $smsUser->fullCellPhoneNumber,
            'SignName'     => $smsUser->signName,
            'TemplateCode' => $templateCode,
        ];
        if (! is_null($smsUser->templateParams)) {
            $this->message['TemplateParam'] = $smsUser->templateParamsStr;
        }

        return $this;
    }
}