<?php

declare(strict_types=1);

namespace Bonn\Line\MessageBuilder;

use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

final class Sticker
{
    const BROWN_CONY_SALLY_PACKAGE_ID = 11537;
    const CHOCO_AND_FRIELD_PACKAGE_ID = 11538;
    const UNIVERSTAR_PACKAGE_ID = 11539;

    public static function randomStickerMessage(): StickerMessageBuilder
    {
        $packageId = self::randomPackageId();
        $strickerId = self::randomStickerId($packageId);

        return new StickerMessageBuilder($packageId, $strickerId);
    }

    public static function randomStickerId(int $packageId): int
    {
        $ids = [];
        switch ($packageId) {
            case self::BROWN_CONY_SALLY_PACKAGE_ID:
                $ids = self::getBrownStickerIds();
                break;
            case self::CHOCO_AND_FRIELD_PACKAGE_ID:
                $ids = self::getChocoStickerIds();
                break;
            case self::UNIVERSTAR_PACKAGE_ID:
                $ids = self::getUniverStarStickerIds();
                break;
        }

        if (empty($ids)) {
            throw new \InvalidArgumentException(\sprintf('Not found sticker package id %s', $packageId));
        }

        shuffle($ids);

        return $ids[0];
    }

    public static function randomPackageId(): int
    {
        $packages = [
            self::BROWN_CONY_SALLY_PACKAGE_ID,
            self::CHOCO_AND_FRIELD_PACKAGE_ID,
            self::UNIVERSTAR_PACKAGE_ID,
        ];

        shuffle($packages);

        return $packages[0];
    }

    public static function getBrownStickerIds(): array
    {
        $start = 52002734;
        $end = 52002773;

        return \range($start, $end);
    }

    public static function getChocoStickerIds(): array
    {
        $start = 51626494;
        $end = 51626533;

        return \range($start, $end);
    }

    public static function getUniverStarStickerIds(): array
    {
        $start = 52114110;
        $end = 52114149;

        return \range($start, $end);
    }
}
