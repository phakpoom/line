<?php

declare(strict_types=1);

namespace Bonn\Line;

use Bonn\Line\Model\LineUser;
use Bonn\Line\Model\LineUserInterface;

final class InMemoryLineUserManager implements LineUserManagerInterface
{
    private $lineUsers = [];
    private $lineUserScopes = [];

    public function findUserFromLineIdentifier(string $lineIdentifier, string $scope): ?LineUserInterface
    {
        return $this->lineUsers[$lineIdentifier . $scope] ?? null;
    }

    public function findEnabledUserFromScope(string $scope): array
    {
        return $this->lineUserScopes[$scope] ?? [];
    }

    public function creteUserWithScope(string $lineIdentifier, string $scope, array $info = []): LineUserInterface
    {
        $user = new LineUser();
        $user->setLineIdentifier($lineIdentifier);
        $user->setScope($scope);
        if (isset($info['displayName'])) {
            $user->setName($info['displayName']);
        }
        $user->setInfo($info);

        $this->save($user);

        return $user;
    }

    public function save(LineUserInterface $lineUser): void
    {
        $this->lineUsers[$lineUser->getLineIdentifier() . $lineUser->getScope()] = $lineUser;
        $this->lineUserScopes[$lineUser->getScope()] = $lineUser;
    }
}
