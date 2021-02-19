<?php

declare(strict_types=1);

namespace Bonn\Line;

use Bonn\Line\Model\LineUserInterface;

interface LineUserManagerInterface
{
    public function findUserFromLineIdentifier(string $lineIdentifier, string $scope): ?LineUserInterface;

    public function findEnabledUserFromScope(string $scope): array;

    public function creteUserWithScope(string $lineIdentifier, string $scope, array $info = []): LineUserInterface;

    public function save(LineUserInterface $lineUser): void;
}
