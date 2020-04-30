<?php

namespace NookPlus;

use Pimple\Container;
use Slim\Interfaces\CallableResolverInterface;

class ActionResolver implements CallableResolverInterface
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($toResolve): callable
    {
        return $this->container[$toResolve];
    }
}
