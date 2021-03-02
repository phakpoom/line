<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle\Test;

use Bonn\Bridge\Symfony\Bundle\LineBotBundle\MessageBuilder\AbstractBuilder;
use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\Test\LineMessageBotTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractLineMessageBotTest extends WebTestCase
{
    use LineMessageBotTestTrait;

    abstract function getBuilder(): AbstractBuilder;
    abstract function getManager(): LineUserManagerInterface;
}
