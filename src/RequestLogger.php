<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-03-03 22:19
 *
 * @link http://crcms.cn/
 *
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Request\Logger;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class RequestLogger
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     *
     * @param $request
     * @param Response $response
     * @return void
     */
    public function handle($request, $response)
    {
        //if ($response)
    }
}