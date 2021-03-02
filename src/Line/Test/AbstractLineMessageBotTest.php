<?php

declare(strict_types=1);

namespace Bonn\Line\Test;

use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\MessageBuilder\AbstractBuilder;
use PHPUnit\Framework\TestCase;

abstract class AbstractLineMessageBotTest extends TestCase
{
    use LineMessageBotTestTrait;

    abstract function getBuilder(): AbstractBuilder;
    abstract function getManager(): LineUserManagerInterface;
}
