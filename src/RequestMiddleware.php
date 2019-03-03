<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-03-03 21:41
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Request\Logger;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestMiddleware
{
    protected $app;

    public function __construct(Container $container)
    {
        $this->app = $container;
    }

    /**
     *
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    /*public function handle($request, Closure $next)
    {
        return $next($request);
    }*/

    /**
     * Record request log
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate($request, $response)
    {

    }
}