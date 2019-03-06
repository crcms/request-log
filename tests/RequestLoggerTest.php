<?php

namespace CrCms\Request\Logger\Tests;

use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use CrCms\Request\Logger\RequestLogger;
use Illuminate\Database\DatabaseManager;

class RequestLoggerTest extends TestCase
{
    public function testRequestLoggerFile()
    {
        $container = new Container();
        $container = \Mockery::mock($container);

        $container->shouldReceive('db')->andReturn(\Mockery::mock(DatabaseManager::class));

        $config = require __DIR__.'/../config/config.php';
        $config['channels']['file']['path'] = __DIR__.'/./request.log';
        $configRepository = new \Illuminate\Config\Repository(['request_logger' => $config]);

        $requestLogger = new RequestLogger($container->db(), $configRepository);

        $config['default'] = 'file';

        $logger = $requestLogger($config);
        $this->assertInstanceOf(\Monolog\Logger::class, $logger);

        $filename = pathinfo($config['channels']['file']['path'], PATHINFO_FILENAME);
        $filename = $filename.'-'.date('Y-m-d').'.log';
        $file = pathinfo($config['channels']['file']['path'], PATHINFO_DIRNAME).'/'.$filename;
        @unlink($file);

        $logger->info('test', []);

        $this->assertEquals(true, file_exists(
            $file
        ));
    }
}
