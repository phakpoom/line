<?php

declare(strict_types=1);

namespace Bonn\Bridge\Symfony\Bundle\LineBotBundle\Controller;

use Bonn\Bridge\Symfony\Bundle\LineBotBundle\MessageBuilder\AbstractBuilder;
use Bonn\Line\LineMessagingBot;
use Bonn\Line\LineUserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LineHookController extends AbstractController
{
    /** @var LineUserManagerInterface  */
    protected $lineUserManager;

    public function __construct(ContainerInterface $container, LineUserManagerInterface $lineUserManager)
    {
        $this->container = $container;
        $this->lineUserManager = $lineUserManager;
    }

    public function hookAction(Request $request, string $builderClass, string $token, string $secret)
    {
        if ('GET' === $request->getMethod()) {
            return Response::create('It\'s Work!!!');
        }

        $bot = new LineMessagingBot($token, $secret, $this->lineUserManager, $this->container->get('bonn_line.logger'));

        /** @var AbstractBuilder $builder */
        $builder = new $builderClass;

        $builder->setContainer($this->container);

        $bot->handleRequestWithBuilder($request->headers->get('x-line-signature'), $request->getContent(), $builder);

        return Response::create('');
    }
}
