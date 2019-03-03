<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-03-03 22:11
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Request\Logger\Tests;

use CrCms\Request\Logger\RequestMiddleware;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestMiddlewareTest
{

    public function testTerminate()
    {

        $container = new Container();

        $middleware = new RequestMiddleware($container);

        $middleware->terminate(Request::capture(), new Response('abc', 200));

    }

}