<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\jobHandlers;

use Codeception\Test\Unit;
use JCIT\jobqueue\jobHandlers\HelloHandler;
use JCIT\jobqueue\jobs\HelloJob;

class HelloHandlerTest extends Unit
{
    public function testHandle()
    {
        $message = 'test';
        $job = new HelloJob($message);

        $handler = new HelloHandler();
        ob_start();
        $handler->handle($job);
        $output = ob_get_clean();
        $this->assertEquals('Hello ' . $message, $output);
    }
}
