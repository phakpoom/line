<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle;

use Bonn\Bridge\Symfony\Bundle\LineBotBundle\DependencyInjection\BonnLineExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BonnLineBotBundle extends Bundle
{
    public function __construct()
    {
        $this->extension = new BonnLineExtension();
    }
}
