<?php


namespace HyPerfEasyWeChat\HttpFoundation;

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext as HyPerfContext;
use Symfony\Component\HttpFoundation\ParameterBag;
use \Symfony\Component\HttpFoundation\Request as Base;

class Request extends Base
{

    public static function createHyPerfFromGlobals()
    {
        $data = self::getHyPerfRequestData();
        $request = self::createHyPerfRequestFromFactory($data['GET'], $data['POST'], [], $data['COOKIE'], $data['FILES'], $data['SERVER']);

        if (0 === strpos($request->headers->get('CONTENT_TYPE'), 'application/x-www-form-urlencoded')
            && \in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), ['PUT', 'DELETE', 'PATCH'])
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }

        return $request;
    }

    public function getContent($asResource = false)
    {
        $container = HyPerfContext::getContainer();
        $request = $container->get(RequestInterface::class);
        $this->content = $request->getBody()->getContents();
        return $this->content;
    }

    private static function createHyPerfRequestFromFactory(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null): self
    {
        if (self::$requestFactory) {
            $request = (self::$requestFactory)($query, $request, $attributes, $cookies, $files, $server, $content);

            if (!$request instanceof self) {
                throw new \LogicException('The Request factory must return an instance of Symfony\Component\HttpFoundation\Request.');
            }

            return $request;
        }

        return new static($query, $request, $attributes, $cookies, $files, $server, $content);

    }

    private static function getHyPerfRequestData()
    {
        $res = [
            'GET' => [],
            'POST' => [],
            'COOKIE' => [],
            'FILES' => [],
            'SERVER' => [],
        ];

        $container = HyPerfContext::getContainer();

        $request = $container->get(RequestInterface::class);

        $res['GET'] = $request->query();
        $res['POST'] = $request->post();
        $serverParams = $request->getServerParams();
        $server = [];
        foreach ($serverParams as $key => $value) {
            $server[mb_strtoupper($key)] = $value;
        }
        $res['SERVER'] = $server;
        $res['COOKIE'] = $request->getCookieParams();
        $upload = $request->getUploadedFiles();
        $files = [];
        foreach ($upload as $key => $value) {
            $temp = $value->toArray();
            $files[$key] = [
                'name' => $temp['name'],
                'type' => $temp['type'],
                'tmp_name' => $temp['tmp_file'],
                'error' => $temp['error'],
                'size' => $temp['size']
            ];
        }
        $res['FILES'] = $files;

        return $res;

    }

}