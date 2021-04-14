<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\components\jobQueues;

use Codeception\Test\Unit;
use JCIT\jobqueue\components\jobQueues\Beanstalk;
use JCIT\jobqueue\components\jobQueues\Synchronous;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\jobs\HelloJob;
use League\Tactician\CommandBus;
use Pheanstalk\Connection;

class BeanstalkTest extends Unit
{
    public function testPut(): void
    {
        $connection = $this->createMock(Connection::class);
        $jobFactory = $this->createMock(JobFactory::class);
        $counter = 0;
        $beforePut = static function(JobInterface $job) use (&$counter) {
            $counter++;
        };

        $jobQueue = $this->getMockBuilder(Beanstalk::class)
            ->setConstructorArgs([$connection, $jobFactory, $beforePut])
            ->onlyMethods(['put'])
            ->getMock();
        ;

        $jobQueue->expects($this->once())->method('put');

        $job = new HelloJob('Test');
        $jobQueue->putJob($job);
        $this->assertEquals(1, $counter);
    }
}
