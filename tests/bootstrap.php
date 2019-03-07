<?php

if (! function_exists('storage_path')) {
    function storage_path($path = '')
    {
        return __DIR__.'/'.$path;
    }
}

$app = new \Illuminate\Container\Container();

$config = require __DIR__.'/../config/config.php';

$app->singleton('config', function () use ($config) {
    return new \Illuminate\Config\Repository([
        'request_logger' => $config,
        'database' => [

        ],
    ]);
});

$app = Mockery::mock($app);
$app->shouldReceive('configPath')->andReturn(__DIR__);

//$providers = [
//    \Illuminate\Database\DatabaseServiceProvider::class,
//    \Jenssegers\Mongodb\MongodbServiceProvider::class,
//];
//
//foreach ($providers as $provider) {
//    (new $provider($app))->register();
//}
//
//foreach ($providers as $provider) {
//    (new $provider($app))->boot();
//}

//$app = Mockery::mock()

return $app;
