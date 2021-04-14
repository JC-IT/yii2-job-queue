<?php
declare(strict_types=1);

namespace JCIT\jobqueue\components\jobQueues;

use Closure;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use Pheanstalk\Connection;
use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;

class Beanstalk extends Pheanstalk implements JobQueueInterface
{
    public function __construct(
        Connection $connection,
        private JobFactoryInterface $jobFactory,
        private ?Closure $beforePut = null
    ) {
        parent::__construct($connection);
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

        $this->put(
            $this->jobFactory->saveToJson($job),
            $priority,
            $delay,
            $ttr
        );
    }
}
