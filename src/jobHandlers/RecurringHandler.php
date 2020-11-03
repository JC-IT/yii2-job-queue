<?php

namespace JCIT\jobqueue\jobHandlers;

use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobHandlerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use JCIT\jobqueue\jobs\RecurringJob;
use JCIT\jobqueue\models\activeRecord\RecurringJob as ActiveRecordRecurringJob;
use yii\behaviors\TimestampBehavior;

/**
 * Class RecurringHandler
 * @package JCIT\jobqueue\jobHandlers
 */
class RecurringHandler implements JobHandlerInterface
{
    /**
     * @var integer
     */
    protected $delay;

    /**
     * @var JobFactoryInterface
     */
    private $jobFactory;

    /**
     * @var JobQueueInterface
     */
    private $jobQueue;

    /**
     * @var integer
     */
    protected $priority;

    /**
     * RecurringHandler constructor.
     * @param JobFactoryInterface $jobFactory
     * @param JobQueueInterface $jobQueue
     * @param int $priority
     * @param int $delay
     */
    public function __construct(
        JobFactoryInterface $jobFactory,
        JobQueueInterface $jobQueue,
        int $priority = 2048,
        int $delay = 1
    ) {
        $this->jobFactory = $jobFactory;
        $this->jobQueue = $jobQueue;
        $this->priority = $priority;
        $this->delay = $delay;
    }

    /**
     * @param ActiveRecordRecurringJob $recurringJob
     * @return JobInterface
     */
    protected function createJob(ActiveRecordRecurringJob $recurringJob): JobInterface
    {
        return $this->jobFactory->createFromArray($recurringJob->task_data);
    }

    /**
     * @param RecurringJob $job
     */
    public function handle(JobInterface $job): void
    {
        /** @var ActiveRecordRecurringJob $recurringJob */
        foreach(ActiveRecordRecurringJob::find()->each() as $recurringJob) {
            try {
                if ($recurringJob->isDue) {
                    $this->jobQueue->putJob($this->createJob($recurringJob),
                        $this->priority,
                        $this->delay
                    );
                    $recurringJob->getBehavior(TimestampBehavior::class)->touch('queued_at');
                }
            } catch (\Throwable $t) {
                \Yii::error($t);
                throw $t;
            }
        }
    }
}