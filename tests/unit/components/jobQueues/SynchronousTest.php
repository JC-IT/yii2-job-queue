<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\components\jobQueues;

use Codeception\Test\Unit;
use JCIT\jobqueue\components\jobQueues\Synchronous;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\jobs\HelloJob;
use League\Tactician\CommandBus;

class SynchronousTest extends Unit
{
    public function testPut(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $counter = 0;
        $beforePut = static function (JobInterface $job) use (&$counter) {
            $counter++;
        };

        $jobQueue = new Synchronous($commandBus, $beforePut);
        $job = new HelloJob('Test');
        $jobQueue->putJob($job);
        $this->assertEquals(1, $counter);
    }
}
