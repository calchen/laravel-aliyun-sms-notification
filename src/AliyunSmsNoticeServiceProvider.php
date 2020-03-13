<?php

namespace Calchen\LaravelAliyunSms;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

class AliyunSmsNoticeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'aliyun_sms');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../lang' => resource_path('lang/vendor/aliyun_sms'),
            ]);
        }

        $this->setupConfig();
    }

    /**
     * 处理配置项.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/aliyun_sms.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                $source => config_path('aliyun_sms.php'),
            ]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('aliyun_sms');
        }

        $this->mergeConfigFrom($source, 'aliyun_sms');
    }

    /**
     * 注册服务
     *
     * @return void
     */
    public function register()
    {
        //
    }
}