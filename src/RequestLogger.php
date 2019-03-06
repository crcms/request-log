<?php

namespace CrCms\Request\Logger;

use CrCms\Log\MongoDBLogger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as MongoLogger;

class RequestLogger extends MongoDBLogger
{
    /**
     * Function
     *
     * @param array $config
     * @return MongoLogger
     */
    public function __invoke(array $config): MongoLogger
    {
        $handler = $config['default'] === 'file' ? $this->fileHandler($config['channels']['file']) : null;//$this->mongoHandler($config['channels']['mongo']);
        $handler->setFormatter(new LineFormatter());
        return new MongoLogger($this->parseChannel($config),[$handler]);
    }

    /**
     * fileLogger
     *
     * @param array $config
     * @return RotatingFileHandler
     */
    protected function fileHandler(array $config): RotatingFileHandler
    {
        return new RotatingFileHandler($config['path'], $config['days'] ?? 7, $this->level($config),
            $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false);
    }
}