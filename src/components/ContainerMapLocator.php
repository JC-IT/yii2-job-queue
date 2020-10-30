<?php

namespace JCIT\jobqueue\components;

use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;
use yii\di\Container;

/**
 * Class ContainerMapLocator
 * @package JCIT\jobqueue\components
 */
class ContainerMapLocator implements HandlerLocator
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    public $map = [];

    /**
     * ContainerMapLocator constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Retrieves the handler for a specified command
     *
     * @param string $commandName
     * @return object
     * @throws MissingHandlerException
     */

    public function getHandlerForCommand($commandName)
    {
        if (!isset($this->map[$commandName])) {
            throw MissingHandlerException::forCommand($commandName);
        }
        return $this->container->get($this->map[$commandName]);
    }

    /**
     * @param string $commandName
     * @param string $handlerService
     */
    public function setHandlerForCommand(string $commandName, string $handlerService)
    {
        $this->map[$commandName] = $handlerService;
    }
}