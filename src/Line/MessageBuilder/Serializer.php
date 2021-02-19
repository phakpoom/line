<?php

declare(strict_types=1);

namespace Bonn\Line\MessageBuilder;

final class Serializer
{
    const MAX_ITEMS_IN_COLUMN = 3;
    const MAX_COLUMNS_IN_CAROUSEL = 10;
    const MAX_ITEMS_IN_CAROUSEL = self::MAX_ITEMS_IN_COLUMN * self::MAX_COLUMNS_IN_CAROUSEL;
    const MAX_CHAR_TITLE_IN_TEMPLATE = 40;
    const MAX_CHAR_LABEL_IN_TEMPLATE = 20;

    public static function label(string $label): string
    {
        return \mb_substr($label, 0, self::MAX_CHAR_LABEL_IN_TEMPLATE);
    }

    public static function title(string $title): string
    {
        return \mb_substr($title, 0, self::MAX_CHAR_TITLE_IN_TEMPLATE);
    }
}
