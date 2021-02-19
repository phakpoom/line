<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle\Manager;

use Bonn\Line\LineUserManagerInterface;
use Bonn\Line\Model\LineUser;
use Bonn\Line\Model\LineUserInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

final class LineUserManager implements LineUserManagerInterface
{
    /** @var string */
    private $lineUserClass;

   /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $objectManager, ?string $lineUserClass = null)
    {
        $this->objectManager = $objectManager;
        $this->lineUserClass = $lineUserClass ?: LineUser::class;
    }

    public function findUserFromLineIdentifier(string $lineIdentifier, string $scope): ?LineUserInterface
    {
        return $this->getRepository()->findOneBy([
            'lineIdentifier' => $lineIdentifier,
            'scope' => $scope,
        ]);
    }

    public function findEnabledUserFromScope(string $scope): array
    {
        return $this->getRepository()->findBy([
            'enabled' => true,
            'scope' => $scope,
        ]);
    }

    public function creteUserWithScope(string $lineIdentifier, string $scope, array $info = []): LineUserInterface
    {
        $lineUser = new $this->lineUserClass;
        $lineUser->setScope($scope);
        $lineUser->setInfo($info);
        $lineUser->setLineIdentifier($lineIdentifier);

        if (isset($info['displayName'])) {
            $lineUser->setName($info['displayName']);
        }

        $this->save($lineUser);

        return $lineUser;
    }

    public function save(LineUserInterface $lineUser): void
    {
        $this->objectManager->persist($lineUser);
        $this->objectManager->flush();
    }

    private function getRepository(): ObjectRepository
    {
        return $this->objectManager->getRepository($this->lineUserClass);
    }
}
