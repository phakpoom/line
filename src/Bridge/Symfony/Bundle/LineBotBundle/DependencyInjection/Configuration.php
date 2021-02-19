<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle\DependencyInjection;

use Bonn\Bridge\Symfony\Bundle\LineBotBundle\LineDumpLogger;
use Bonn\Bridge\Symfony\Bundle\LineBotBundle\Manager\LineUserManager;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bonn_line');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
