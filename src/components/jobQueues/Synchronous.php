<?php
declare(strict_types=1);

namespace JCIT\jobqueue\components\jobQueues;

use Closure;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use League\Tactician\CommandBus;
use Pheanstalk\Contract\PheanstalkPublisherInterface;

class Synchronous implements JobQueueInterface
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ?Closure $beforePut = null
    ) {
    }

    public function putJob(
        JobInterface $job,
        int $priority = PheanstalkPublisherInterface::DEFAULT_PRIORITY,
        int $delay = PheanstalkPublisherInterface::DEFAULT_DELAY,
        int $ttr = PheanstalkPublisherInterface::DEFAULT_TTR
    ): void {
        if (isset($this->beforePut)) {
            ($this->beforePut)($job);
        }

        $this->commandBus->handle($job);
    }
}
