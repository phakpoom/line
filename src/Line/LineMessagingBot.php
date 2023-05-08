<?php

declare(strict_types=1);

namespace Bonn\Line;

use Bonn\Line\MessageBuilder\AbstractBuilder;
use LINE\LINEBot;
use Psr\Log\LoggerInterface;

class LineMessagingBot
{
    public static $lastReplyMessage = null;
    public static $lastMessageEvent = null;

    /** @var LINEBot|null */
    private $bot;

    /** @var LineUserManagerInterface */
    private $lineUserManager;

    /** @var LoggerInterface|null */
    private $logger;

    public function __construct(string $token, string $secret, LineUserManagerInterface $lineUserManager, ?LoggerInterface $logger = null)
    {
        $httpClient = new LINEBot\HTTPClient\CurlHTTPClient($token);
        $this->bot = new LINEBot($httpClient, ['channelSecret' => $secret]);
        $this->lineUserManager = $lineUserManager;
        $this->logger = $logger;
    }

    public function getBot(): LINEBot
    {
        return $this->bot;
    }

    public function getLineUserManager(): LineUserManagerInterface
    {
        return $this->lineUserManager;
    }

    public function handleRequestWithBuilder(string $signature, string $requestBody, AbstractBuilder $builder): void
    {
        self::$lastReplyMessage = null;
        self::$lastMessageEvent = null;

        /** @var LINEBot\Event\MessageEvent $messageEvent */
        $replyMessages = [];
        foreach ($this->bot->parseEventRequest($requestBody, $signature) as $messageEvent) {
            self::$lastMessageEvent = $messageEvent;

            $lineUser = $this->lineUserManager->findUserFromLineIdentifier($messageEvent->getUserId(), $builder::getScope());

            // มีการ require verify ป่าว ?
            if (!empty($builder::getRegisterText())) {
                if ($messageEvent instanceof LINEBot\Event\MessageEvent\TextMessage && $builder::getRegisterText() === $messageEvent->getText()) {
                    if (null === $lineUser) {
                        $lineUser = $this->lineUserManager->creteUserWithScope($messageEvent->getUserId(), $builder::getScope(), $this->getProfileAsArray($messageEvent->getUserId()));
                        $lineUser->setEnabled(false);

                        $this->lineUserManager->save($lineUser);

                        if ($builder::getRegisterAcceptText($lineUser->getName())) {
                            $this->bot->replyMessage($messageEvent->getReplyToken(), $m = new LINEBot\MessageBuilder\TextMessageBuilder($builder::getRegisterAcceptText($lineUser->getName())));
                            self::$lastReplyMessage = $m;
                        }

                        continue;
                    }
                }

                if (null === $lineUser || !$lineUser->isEnabled()) {
                    $builder->buildMessage(null, $messageEvent, $replyMessages);

                    continue;
                }
            }

            if (null === $lineUser) {
                $lineUser = $this->lineUserManager->creteUserWithScope($messageEvent->getUserId(), $builder::getScope(), $this->getProfileAsArray($messageEvent->getUserId()));
            }

            $builder->buildMessage($lineUser, $messageEvent, $replyMessages);
        }

        foreach ($replyMessages as $messages) {
            $response = $this->bot->replyMessage($messages['reply_token'], $messages['message']);

            self::$lastReplyMessage = $messages['message'];

            if ($response->isSucceeded()) {
                continue;
            }

            $this->logger?->critical($response->getRawBody());
        }
    }

    private function getProfileAsArray(string $userId): array
    {
        /* Example
         * array:5 [
          "userId" => "U99b35a03a421998a1b1f4331b116c753"
          "displayName" => "Bon"
          "pictureUrl" => "https://profile.line-scdn.net/0hIE7KOtxdFmoKMjxKByRpPTZ3GAd9HBAiclZYXytmHV0gV1ZvYQYOCSc0T1ojBgRsNAQMWCxnGlIm"
          "statusMessage" => "(Lamb Chop)"
          "language" => "th"
        ]
         */
        $res = $this->bot->getProfile($userId);

        return $res->getJSONDecodedBody();
    }
}
