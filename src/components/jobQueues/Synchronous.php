<?php
declare(strict_types=1);

namespace JCIT\jobqueue\components\jobQueues;

use Closure;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use League\Tactician\CommandBus;
use Pheanstalk\Contract\PheanstalkInterface;

class Synchronous implements JobQueueInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private ?Closure $beforePut = null
    ) {
    }

    public function putJob(
        JobInterface $job,
        int $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        int $delay = PheanstalkInterface::DEFAULT_DELAY,
        int $ttr = PheanstalkInterface::DEFAULT_TTR
    ): void {
        if (isset($this->beforePut)) {
            ($this->beforePut)($job);
        }

        $this->commandBus->handle($job);
    }
}
