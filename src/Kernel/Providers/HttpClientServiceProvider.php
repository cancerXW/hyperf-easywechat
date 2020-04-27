<?php

namespace HyPerfEasyWeChat\Kernel\Providers;

use EasyWeChat\Kernel\Providers\HttpClientServiceProvider as ServiceProvider;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use Pimple\Container;

class HttpClientServiceProvider extends ServiceProvider
{
    public function register(Container $pimple)
    {
        $pimple['http_client'] = function ($app) {
            return new Client(array_merge(
                $app['config']->get('http', []),
                [
                    'handler' => HandlerStack::create(new CoroutineHandler())
                ]
            ));
        };
    }

}