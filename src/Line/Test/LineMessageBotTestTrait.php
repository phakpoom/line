<?php

declare(strict_types=1);

namespace Bonn\Line\Test;

use Bonn\Line\LineMessagingBot;
use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\MessageBuilder\AbstractBuilder;
use LINE\LINEBot;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

trait LineMessageBotTestTrait
{
    public function assertThatBotReplyTextMessage(string $input, string $userId, ?string $text)
    {
        $this->invoke($input, $userId);

        if (null === $text) {
            $this->assertEmpty(LineMessagingBot::$lastReplyMessage);
        } elseif ('sticker' === $text) {
            $this->assertInstanceOf(LINEBot\MessageBuilder\StickerMessageBuilder::class, LineMessagingBot::$lastReplyMessage);
        } else {
            $this->assertEquals(LineMessagingBot::$lastReplyMessage, new LINEBot\MessageBuilder\TextMessageBuilder($text));
        }
    }

    public function assertThatBotReplyBuilt(string $input, string $userId, array $carousel)
    {
        $this->invoke($input, $userId);

        $message = LineMessagingBot::$lastReplyMessage;

        $this->assertEquals($carousel, $message->buildMessage());
    }

    private function invoke(string $input, string $userId)
    {
        $service = new LineMessagingBot('_TOKEN_', '_SECRET_', $this->getManager());
        /** @var LINEBot $fakeBot */
        $fakeBot = $this->prophesize(LINEBot::class);

        $reflection = new \ReflectionClass($service);
        $property = $reflection->getProperty('bot');
        $property->setAccessible(true);
        $property->setValue($service, $fakeBot->reveal());

        $fakeBot->parseEventRequest(Argument::any(), Argument::any())->willReturn([
            new LINEBot\Event\MessageEvent\TextMessage([
                'replyToken' => uniqid(),
                'message' => [
                    'id' => uniqid(),
                    'text' => $input
                ],
                'source' => [
                    'userId' => $userId
                ]
            ])
        ]);

        $fakeBot->getProfile($userId)->willReturn(new LINEBot\Response(200, \json_encode([
            "userId" => $userId,
            "displayName" => $userId,
            "pictureUrl" => $userId,
            "statusMessage" => $userId,
            "language" => "th",
        ])));

        $fakeBot->replyMessage(Argument::any(), Argument::any())->willReturn(new LINEBot\Response(200, \json_encode([])));

        $builder = $this->getBuilder();
        $service->handleRequestWithBuilder('__', '__', $builder);

        return LineMessagingBot::$lastReplyMessage;
    }
}
