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
use Illuminate\Http\Request;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggerMiddleware
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->app = $container;
    }

    /**
     * middleware handle.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        define('REQUEST_START', microtime(true));

        if ($this->app['config']['request_logger']['enable_sql_log']) {
            $this->app['db']->enableQueryLog();
        }

        return $next($request);
    }

    /**
     * Record request log.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate($request, $response)
    {
        $formatter = $this->app['request.formatter'];
        $message = $formatter->setInstances([
            'request' => $request,
            'response' => $response,
            'auth' => $this->app['auth'],
        ])->message();

        $context = $formatter->context();

        $status = $response->getStatusCode();
        $requestLogger = $this->app['request.logger']($this->app['config']['request_logger']);

        if ($status >= 500) {
            $requestLogger->error($message, $context);
        } elseif ($status >= 400) {
            $requestLogger->warning($message, $context);
        } else {
            $requestLogger->info($message, $context);
        }

        $this->app['db']->disableQueryLog();
    }
}
