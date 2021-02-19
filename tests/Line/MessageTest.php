<?php

declare(strict_types=1);

namespace Tests\Line;

use Bonn\Line\InMemoryLineUserManager;
use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\MessageBuilder\AbstractBuilder;
use Bonn\Line\Test\AbstractLineMessageBotTest;

class MessageTest extends AbstractLineMessageBotTest
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
            }

            public static function getScope(): string
            {
                return 'test';
            }

            public static function getRegisterAcceptText(?string $name): string
            {
                return "สวัสดี $name";
            }

            public static function getRegisterText(): string
            {
                return 'สมัคร';
            }

            public static function getFallbackMessage()
            {
                return 'งง';
            }
        };
    }

    public function testBasic()
    {
        // no register
        $this->assertThatBotReplyTextMessage('ดีจ้า', 'bon', null);

        // register // register success
        $this->assertThatBotReplyTextMessage('สมัคร', 'bon', 'สวัสดี bon');


        // accept
        $bonUser = $this->manager->findUserFromLineIdentifier('bon', 'test');
        $this->assertNotEmpty($bonUser);
        $bonUser->setEnabled(true);
        $this->manager->save($bonUser);

        // fallback
        $this->assertThatBotReplyTextMessage('ดีจ้า', 'bon', 'งง');
        $this->assertThatBotReplyTextMessage('ถาม', 'bon', 'ตอบ');
    }
}
