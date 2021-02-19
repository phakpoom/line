<?php

declare(strict_types=1);

namespace Tests\Line;

use Bonn\Line\InMemoryLineUserManager;
use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\MessageBuilder\AbstractBuilder;
use Bonn\Line\Test\AbstractLineMessageBotTest;

class SimpleBuilderTest extends AbstractLineMessageBotTest
{
    private $manager;
    protected function setUp()
    {
        parent::setUp();

        $this->manager = new InMemoryLineUserManager();
    }

    public function getManager(): LineUserManagerInterface
    {
        return $this->manager;
    }

    public function getBuilder(): AbstractBuilder
    {
        return new class extends AbstractBuilder {
            public function getTextTemplates(): \Generator
            {
                yield 'ถาม' => 'ตอบ';
                yield '/(\d+)/' => function (array $m) {
                    return $m[0];
                };
            }

            public static function getScope(): string
            {
                return 'test';
            }

            public static function getFallbackMessage()
            {
                return 'งง';
            }
        };
    }

    public function testSimple()
    {
        $this->assertThatBotReplyTextMessage('ดีจ้า', 'bon', 'งง');
        $this->assertThatBotReplyTextMessage('ถาม', 'bon', 'ตอบ');
        $this->assertThatBotReplyTextMessage('1', 'bon', '1');
        $this->assertThatBotReplyTextMessage('2', 'bon', '2');
    }
}
