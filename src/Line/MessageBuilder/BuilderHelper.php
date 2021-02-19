<?php

declare(strict_types=1);

namespace Bonn\Line\MessageBuilder;

use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

final class BuilderHelper
{
    public static function buildCarouselMessageColumnTemplateBuilder(
        array $allMessageActions,
        string $title,
        string $subTitle = '...'
    ): array
    {
        $columns = [];
        foreach (\array_chunk($allMessageActions, Serializer::MAX_ITEMS_IN_COLUMN) as $arr) {
            if (\count($arr) < Serializer::MAX_ITEMS_IN_COLUMN) {
                // fill array
                $arr = \array_merge($arr, \array_fill(\count($arr) - 1, Serializer::MAX_ITEMS_IN_COLUMN - \count($arr), new MessageTemplateActionBuilder('...', '..')));
            }

            $columns[] = new CarouselColumnTemplateBuilder($title, $subTitle, null, $arr);
        }

        return $columns;
    }
}
