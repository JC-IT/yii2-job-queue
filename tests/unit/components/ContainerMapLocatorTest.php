<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\components;

use Codeception\Test\Unit;
use JCIT\jobqueue\components\ContainerMapLocator;
use JCIT\jobqueue\jobHandlers\HelloHandler;
use JCIT\jobqueue\jobs\HelloJob;
use League\Tactician\Exception\MissingHandlerException;
use yii\di\Container;

class ContainerMapLocatorTest extends Unit
{
    public function testMissingHandler(): void
    {
        $container = $this->createMock(Container::class);
        $containerMapLocator = new ContainerMapLocator($container);
        $this->expectException(MissingHandlerException::class);
        $containerMapLocator->getHandlerForCommand(HelloJob::class);
    }

    public function testGetHandler(): void
    {
        $container = $this->createMock(Container::class);
        $handler = new HelloHandler();
        $container->method('get')->willReturn($handler);

        $containerMapLocator = new ContainerMapLocator($container);
        $containerMapLocator->setHandlerForCommand(HelloJob::class, HelloHandler::class);
        $this->assertEquals($handler, $containerMapLocator->getHandlerForCommand(HelloJob::class));
    }
}
