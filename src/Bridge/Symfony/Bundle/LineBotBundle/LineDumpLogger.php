<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LineDumpLogger extends NullLogger implements LoggerInterface
{
    public function critical($message, array $context = array())
    {
        if (\function_exists('dump')) {
            dump($message);
        }
    }
}
