<?php

namespace Calchen\LaravelAliyunSms;

use Illuminate\Support\Str;

/**
 * Class SmsUser
 *
 * @package App\Messages
 * @property string     countryCallingCode
 * @property string     cellPhoneNumber
 * @property string     signName
 * @property string     fullCellPhoneNumber
 * @property array|null templateParams
 * @property array|null templateParamsStr
 */
class SmsUser
{
    const CHINESE_MAINLAND_COUNTRY_CALLING_CODE = '86';

    /**
     * @var string 国家码
     */
    protected $countryCallingCode;

    /**
     * @var string 手机号
     */
    protected $cellPhoneNumber;

    /**
     * @var string 签名名称
     */
    protected $signName;

    /**
     * @var array|null 模板参数
     */
    protected $templateParams;

    public function __construct(
        string $countryCallingCode,
        string $cellPhoneNumber,
        string $signName,
        array $templateParams = null
    ) {
        $this->countryCallingCode = $countryCallingCode;
        $this->cellPhoneNumber = $cellPhoneNumber;
        $this->signName = $signName;
        $this->templateParams = $templateParams;
    }

    /**
     * @return string
     */
    public function getCountryCallingCode(): string
    {
        return $this->countryCallingCode;
    }

    /**
     * @return string
     */
    public function getCellPhoneNumber(): string
    {
        return $this->cellPhoneNumber;
    }

    /**
     * @return string
     */
    public function getSignName(): string
    {
        return $this->signName;
    }

    /**
     * 根据国家码和手机号获取完整的手机号，这里的规则是：
     * 国家码为 86 的中国大陆的手机号，只需要手机号，其他地区需要将国家码和手机号拼接
     *
     * @link https://help.aliyun.com/document_detail/101414.html?spm=a2c4g.11186623.6.624.e3fd202acwSt5b#h2-url-2
     *
     * @return string
     */
    public function getFullCellPhoneNumber()
    {
        return $this->countryCallingCode == self::CHINESE_MAINLAND_COUNTRY_CALLING_CODE ?
            $this->cellPhoneNumber : "{$this->countryCallingCode}{$this->cellPhoneNumber}";
    }

    /**
     * @return array|null
     */
    public function getTemplateParams()
    {
        return $this->templateParams;
    }

    /**
     * @return string|null
     */
    public function getTemplateParamsStr()
    {
        return $this->templateParams ? json_encode($this->templateParams) : null;
    }

    public function __get($name)
    {
        $name = 'get'.Str::ucfirst($name);
        if (method_exists($this, $name)) {
            return $this->{$name}();
        }

        return null;
    }
}