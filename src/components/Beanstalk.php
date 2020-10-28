<?php

namespace JCIT\jobqueue\components;

use JCIT\jobqueue\events\BeanstalkEvent;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use Pheanstalk\Connection;
use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;

/**
 * Class Beanstalk
 * @package common\components
 */
class Beanstalk extends Pheanstalk implements JobQueueInterface
{
    /**
     * @var JobFactory
     */
    protected $jobFactory;

    /**
     * Beanstalk constructor.
     * @param Connection $connection
     * @param JobFactory $jobFactory
     */
    public function __construct(
        Connection $connection,
        JobFactory $jobFactory
    ) {
        $this->jobFactory = $jobFactory;
        parent::__construct($connection);
    }

    /**
     * @param string $data
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     * @return Job
     */
    public function put(
        string $data,
        int $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        int $delay = PheanstalkInterface::DEFAULT_DELAY,
        int $ttr = PheanstalkInterface::DEFAULT_TTR
    ): Job {
        $result = parent::put($data, $priority, $delay, $ttr);
        $event = new BeanstalkEvent($result);
        \Yii::$app->trigger($event::EVENT_JOB_SUBMITTED, $event);
        return $result;
    }

    /**
     * @param JobInterface $task
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     */
    public function putJob(
        JobInterface $task,
        int $priority = PheanstalkInterface::DEFAULT_PRIORITY,
        int $delay = PheanstalkInterface::DEFAULT_DELAY,
        int $ttr = PheanstalkInterface::DEFAULT_TTR
    ): void {
        $this->put(
            $this->jobFactory->saveToJson($task),
            $priority,
            $delay,
            $ttr
        );
    }
}
