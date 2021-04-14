<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\events;

use Codeception\Test\Unit;
use JCIT\jobqueue\events\JobQueueEvent;
use JCIT\jobqueue\jobs\HelloJob;

class JobQueueEventTest extends Unit
{
    public function testGetJob(): void
    {
        $name = 'Test';
        $job = new HelloJob($name);

        $jobQueueEvent = new JobQueueEvent($job);
        $this->assertEquals($name, $jobQueueEvent->getJob()->getName());
    }
}
