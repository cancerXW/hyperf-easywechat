<?php

namespace HyPerfEasyWeChat;

use EasyWeChat\Kernel\Support\Str;
use HyPerfEasyWeChat\Kernel\Providers\HttpClientServiceProvider;

class Factory
{
    public static function make($name, array $config)
    {
        $namespace = Str::studly($name);
        $application = "\\EasyWeChat\\{$namespace}\\Application";
        $app = new $application($config);
        // 替换原来的httpClientServiceProject
        $app->registerProviders([
            HttpClientServiceProvider::class
        ]);
        return $app;
    }

    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

}