<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-03-03 22:02
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Request\Logger;

use Illuminate\Support\ServiceProvider;

class RequestLoggerServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var string
     */
    protected $namespace = 'request-logger';

    /**
     * @var string
     */
    protected $packagePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->packagePath.'config/config.php' => $this->namespace.'.php',
        ]);
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath.'config/config.php', $this->namespace);

        $this->app->alias('request.logger', RequestLogger::class);
        $this->app->singleton('request.logger', function ($app) {
            return new RequestLogger($this->app['log']->stack(
                $this->app['config']->get('request-logger.default', 'file')
            ));
        });
    }

    /**
     * Providers
     *
     * @return array
     */
    public function provides(): array
    {
        return ['request.logger', RequestLogger::class];
    }
}