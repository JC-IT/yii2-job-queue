<?php

namespace JCIT\jobqueue\components\jobQueues;

use JCIT\jobqueue\events\JobQueueEvent;
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
        private JobFactoryInterface $jobFactory
    ) {
        parent::__construct($connection);
    }

    public function putJob(
        JobInterface $job,
        int $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        int $delay = PheanstalkInterface::DEFAULT_DELAY,
        int $ttr = PheanstalkInterface::DEFAULT_TTR
    ): void {
        $event = new JobQueueEvent($job);
        \Yii::$app->trigger($event::EVENT_JOB_QUEUE_PUT, $event);

        $this->put(
            $this->jobFactory->saveToJson($job),
            $priority,
            $delay,
            $ttr
        );
    }
}
