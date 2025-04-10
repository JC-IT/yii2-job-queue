<?php
declare(strict_types=1);

namespace JCIT\jobqueue\components\jobQueues;

use Closure;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use Pheanstalk\Contract\PheanstalkPublisherInterface;
use Pheanstalk\Pheanstalk;

class Beanstalk implements JobQueueInterface
{
    public function __construct(
        private readonly Pheanstalk $pheanstalk,
        private readonly JobFactoryInterface $jobFactory,
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

        $this->pheanstalk->put(
            $this->jobFactory->saveToJson($job),
            $priority,
            $delay,
            $ttr
        );
    }
}
