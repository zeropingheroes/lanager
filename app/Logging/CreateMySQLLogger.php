<?php

namespace Zeropingheroes\Lanager\Logging;

use Exception;
use Monolog\Logger;
use Logger\Monolog\Handler\MysqlHandler;

class CreateMySQLLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array $config
     * @return Logger
     * @throws Exception
     */
    public function __invoke(array $config)
    {
        $channel = $config['name'] ?? env('APP_ENV');
        $monolog = new Logger($channel);
        $monolog->pushHandler(new MysqlHandler());
        return $monolog;
    }
}