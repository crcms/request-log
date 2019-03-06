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
use CrCms\Request\Logger\Contracts\FormatterContract;
use CrCms\Request\Logger\Formatter\AbstractFormatter;

class RequestLoggerServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * @var string
     */
    protected $namespace = 'request_logger';

    /**
     * @var string
     */
    protected $packagePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;

    /**
     * Boot.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->packagePath.'config/config.php' => $this->app->configPath($this->namespace.'.php'),
        ]);
    }

    /**
     * Register.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath.'config/config.php', $this->namespace);

        $this->registerAlias();

        $this->registerServices();
    }

    /**
     * registerAlias.
     *
     * @return void
     */
    protected function registerAlias(): void
    {
        $this->app->alias('request.logger', RequestLogger::class);
        $this->app->alias('request.formatter', FormatterContract::class);
        $this->app->alias('request.formatter', AbstractFormatter::class);
    }

    /**
     * registerServices.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->singleton('request.logger', function ($app) {
            return new RequestLogger($app['db'], $app['config']);
        });

        $this->app->singleton('request.formatter', function ($app) {
            $formatter = $app['config']['request_logger']['formatter'];

            return new $formatter($app, $app['config']['request_logger']['message']);
        });
    }

    /**
     * Providers.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'request.logger',
            'request.formatter',
            RequestLogger::class,
            FormatterContract::class,
            AbstractFormatter::class,
        ];
    }
}
