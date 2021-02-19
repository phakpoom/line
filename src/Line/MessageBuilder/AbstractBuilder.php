<?php

declare(strict_types=1);

namespace Bonn\Line\MessageBuilder;

use Bonn\Line\Model\LineUserInterface;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;

abstract class AbstractBuilder
{
    const ALL_CHAR_REGEX = '/^(.)+$/';

    /** @var LineUserInterface */
    protected $lineUserContext;

    abstract public static function getRegisterText(): string;

    abstract public static function getRegisterAcceptText(?string $name): string;

    /**
     * @return string|null|MessageBuilder
     */
    abstract public static function getFallbackMessage();

    abstract public static function getScope(): string;

    abstract public function getTextTemplates(): \Generator;

    public function getReplyTextMessage(string $text): ?MessageBuilder
    {
        foreach ($this->getTextTemplates() as $pattern => $result) {
            $arg = null;
            // is regex
            if ('/' === $pattern[0] && '/' === $pattern[\strlen($pattern) - 1]) {
                if (!\preg_match($pattern, $text, $matches)) {
                    continue;
                }

                $arg = $matches;
            } else {
                // is text
                if ($text !== $pattern) {
                    continue;
                }
            }

            return $this->parseReply(\is_callable($result) ? \call_user_func($result, $arg) : $result);
        }

        if (empty(static::getFallbackMessage())) {
            return null;
        }

        return $this->parseReply(static::getFallbackMessage());
    }

    public function buildMessage(LineUserInterface $lineUser, MessageEvent $messageEvent, array &$replyMessages): void
    {
        $this->lineUserContext = $lineUser;

        if (empty($messageEvent->getReplyToken())) {
            return;
        }

        $builtMessage = null;

        if ($messageEvent instanceof MessageEvent\TextMessage) {
            /** @var $messageEvent MessageEvent\TextMessage */
            $builtMessage = $this->getReplyTextMessage($messageEvent->getText());
        }

        if (empty($builtMessage)) {
            return;
        }

        $replyMessages[] = [
            'reply_token' => $messageEvent->getReplyToken(),
            'message' => $builtMessage,
        ];
    }

    private function parseReply($v): MessageBuilder
    {
        if (is_string($v)) {
            return new MessageBuilder\TextMessageBuilder($v);
        }

        return $v;
    }

    protected function setUserActiveState(?string $state, array $info = []): void
    {
        $arr = $this->lineUserContext->getLineState();
        if (null === $state) {
            unset($arr['s']);
        } else {
            $arr['s'] = [
                'key' => $state,
                'info' => $info
            ];
        }

        $this->lineUserContext->setLineState($arr);
    }

    protected function getUserActiveState(): array
    {
        $arr = $this->lineUserContext->getLineState();

        return [@$arr['s']['key'], (array) @$arr['s']['info']];
    }
}
