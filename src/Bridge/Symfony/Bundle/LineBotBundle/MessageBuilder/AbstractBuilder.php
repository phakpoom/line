<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle\MessageBuilder;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

abstract class AbstractBuilder extends \Bonn\Line\MessageBuilder\AbstractBuilder
{
    use ContainerAwareTrait;

    protected function setUserActiveState(?string $state, array $info = []): void
    {
        parent::setUserActiveState($state, $info);

        $this->container->get('doctrine.orm.default_entity_manager')->flush();
    }
}
