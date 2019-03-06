<?php

namespace CrCms\Request\Logger\Formatter;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\Response;
use CrCms\Request\Logger\Contracts\FormatterContract;

class LaravelFormatter extends AbstractFormatter implements FormatterContract
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $instances;

    /**
     * @param Container $container
     * @param string $message
     */
    public function __construct(Container $container, string $message)
    {
        parent::__construct($message);
        $this->container = $container;
    }

    /**
     * setResponse.
     *
     * @param Response $response
     * @return self
     */
    public function setInstances(array $instances): self
    {
        $this->instances = $instances;

        return $this;
    }

    /**
     * keywords.
     *
     * @param Request $request
     * @return array
     */
    protected function keywords(): array
    {
        return [
            'request' => [
                'method' => function ($request) {
                    /* @var Request $request */
                    return $request->method();
                },
                'url' => function ($request) {
                    /* @var Request $request */
                    return $request->fullUrl();
                },
                'ip' => function ($request) {
                    /* @var Request $request */
                    return $request->getClientIp();
                },
                'agent' => function ($request) {
                    /* @var Request $request */
                    return $request->userAgent();
                },
                'scheme' => function ($request) {
                    /* @var Request $request */
                    return $request->getScheme();
                },
                'route' => function ($request) {
                    /* @var Request $request */
                    $route = $request->route();

                    return is_null($route) ? '' : $route->getName();
                },
            ],
            'response' => [
                'status_code' => function ($response) {
                    /* @var Response $response */
                    return $response->getStatusCode();
                },
                'response_time' => function ($response) {
                    /* @var Response $response */
                    if (defined('LARAVEL_START')) {
                        $start = LARAVEL_START;
                    } elseif (defined('REQUEST_START')) {
                        $start = REQUEST_START;
                    } else {
                        return -1;
                    }

                    return microtime(true) - $start;
                },
            ],

            'auth' => [
                'user' => function (AuthManager $auth = null) {
                    return $auth ? $auth->id() : -1;
                },
            ],

            'db' => [
                'db_count' => function (DatabaseManager $db) {
                    count($db->getQueryLog());
                },
            ],
        ];
    }

    /**
     * instances.
     *
     * @return array
     */
    protected function instances(): array
    {
        return array_merge([
            'db' => $this->container->make('db'),
            'auth' => $this->container->make('auth'),
        ], $this->instances);
    }
}
