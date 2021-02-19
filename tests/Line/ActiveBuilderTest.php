<?php

declare(strict_types=1);

namespace Tests\Line;

use Bonn\Line\InMemoryLineUserManager;
use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\MessageBuilder\AbstractBuilder;
use Bonn\Line\Test\AbstractLineMessageBotTest;
use function foo\func;

class ActiveBuilderTest extends AbstractLineMessageBotTest
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
                [$active, $info] = $this->getUserActiveState();

                if ('กินไร' === $active) {
                    yield '/(ข้าว|ก๋วยเตี๋ยว)/' => function (array $m) {
                        $this->setUserActiveState(null);

                        return "โอเตเดี๋ยวสั่ง{$m[1]}ให้";
                    };

                    yield self::ALL_CHAR_REGEX => function (array $m) {
                        $this->setUserActiveState(null);

                        return "สิ้นสุดคำสั่ง! กรุณาเลือกระหว่างข้าวกับก๋วยเตี๋ยว";
                    };
                }

                yield 'กินไร' => function () {
                    $this->setUserActiveState('กินไร');

                    return 'ข้าวหรือก๋วยเตี๋ยว';
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

    public function testVerify()
    {
        $this->assertThatBotReplyTextMessage('ดีจ้า', 'bon', 'งง');
        $this->assertThatBotReplyTextMessage('กินไร', 'bon', 'ข้าวหรือก๋วยเตี๋ยว');
        $this->assertThatBotReplyTextMessage('กินไร', 'bon', 'สิ้นสุดคำสั่ง! กรุณาเลือกระหว่างข้าวกับก๋วยเตี๋ยว');

        $this->assertThatBotReplyTextMessage('กินไร', 'bon', 'ข้าวหรือก๋วยเตี๋ยว');
        $this->assertThatBotReplyTextMessage('ข้าว', 'bon', 'โอเตเดี๋ยวสั่งข้าวให้');

        $this->assertThatBotReplyTextMessage('กินไร', 'bon', 'ข้าวหรือก๋วยเตี๋ยว');
        $this->assertThatBotReplyTextMessage('ก๋วยเตี๋ยว', 'bon', 'โอเตเดี๋ยวสั่งก๋วยเตี๋ยวให้');
    }
}
