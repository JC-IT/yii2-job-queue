<?php

namespace JCIT\jobqueue\components;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use yii\di\Container;

class ContainerMapLocator implements HandlerLocator
{
    private array $map = [];

    public function __construct(
        private Container $container
    ) {
    }

    /**
     * @param string $commandName
     */
    public function getHandlerForCommand($commandName): object
    {
        if (!isset($this->map[$commandName])) {
            throw MissingHandlerException::forCommand($commandName);
        }
        return $this->container->get($this->map[$commandName]);
    }

    public function setHandlerForCommand(string $commandName, string $handler)
    {
        $this->map[$commandName] = $handler;
    }
}
