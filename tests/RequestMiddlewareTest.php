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

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\DatabaseManager;
use CrCms\Request\Logger\RequestMiddleware;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response;
use CrCms\Request\Logger\RequestLoggerServiceProvider;

class RequestMiddlewareTest extends TestCase
{
    use ApplicationTrait;

    public function testMiddlewareFile()
    {
        static::$app->singleton('db', function () {
            $db = \Mockery::mock(DatabaseManager::class);
            $db->shouldReceive('getQueryLog')->andReturn([]);
            $db->shouldReceive('enableQueryLog');
            $db->shouldReceive('disableQueryLog');

            return $db;
        });
        static::$app->singleton('auth', function () {
            $auth = \Mockery::mock(Authenticatable::class);
            $auth->shouldReceive('getAuthIdentifierName')->andReturn('name');
            $auth->shouldReceive('getAuthIdentifier')->andReturn(1);

            return $auth;
        });

        $provider = new RequestLoggerServiceProvider(static::$app);

        $provider->register();
        $provider->boot();

        $middleware = new RequestMiddleware(static::$app);

        $request = Request::capture();

        $filename = pathinfo(static::$app['config']['request_logger']['channels']['file']['path'], PATHINFO_FILENAME);
        $filename = $filename.'-'.date('Y-m-d').'.log';
        $file = pathinfo(static::$app['config']['request_logger']['channels']['file']['path'], PATHINFO_DIRNAME).'/'.$filename;
        @unlink($file);

        $response = $middleware->handle($request, function () {
            return new Response('abc');
        });

        $middleware->terminate($request, $response);

        $this->assertEquals(true, file_exists($file));
    }
}
