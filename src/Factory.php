<?php

namespace HyPerfEasyWeChat;

use EasyWeChat\Kernel\Support\Str;
use HyPerfEasyWeChat\Kernel\Providers\HttpClientServiceProvider;
use HyPerfEasyWeChat\Kernel\Providers\RequestServiceProvider;

class Factory
{
    public static function make($name, array $config)
    {
        $namespace = Str::studly($name);
        $application = "\\EasyWeChat\\{$namespace}\\Application";
        $app = new $application($config);
        // 替换原来的httpClientServiceProject RequestServiceProvider
        $app->registerProviders([
            HttpClientServiceProvider::class,
            RequestServiceProvider::class
        ]);
        return $app;
    }

    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

}