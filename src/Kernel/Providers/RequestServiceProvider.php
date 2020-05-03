<?php

namespace HyPerfEasyWeChat\Kernel\Providers;

use EasyWeChat\Kernel\Providers\HttpClientServiceProvider as ServiceProvider;

use HyPerfEasyWeChat\HttpFoundation\Request;
use Pimple\Container;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['request'] = function () {
            return Request::createHyPerfFromGlobals();
        };
    }
}