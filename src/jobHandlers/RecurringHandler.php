<?php

namespace JCIT\jobqueue\jobHandlers;

use Closure;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobHandlerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobQueueInterface;
use JCIT\jobqueue\jobs\RecurringJob;
use JCIT\jobqueue\models\activeRecord\RecurringJob as ActiveRecordRecurringJob;
use yii\base\InvalidArgumentException;
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
    public $delay = 1;

    /**
     * @var Closure
     */
    public $jobCreatedCallback;

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
    public $priority = 2048;

    /**
     * @var Closure
     */
    public $queryModifier;

    /**
     * @var string
     */
    public $queuedAtAttribute = 'queuedAt';

    /**
     * @var string
     */
    public $recurringJobClass = RecurringJob::class;

    /**
     * @var string
     */
    public $jobDataAttribute = 'jobData';

    /**
     * RecurringHandler constructor.
     * @param JobFactoryInterface $jobFactory
     * @param JobQueueInterface $jobQueue
     */
    public function __construct(
        JobFactoryInterface $jobFactory,
        JobQueueInterface $jobQueue
    ) {
        $this->jobFactory = $jobFactory;
        $this->jobQueue = $jobQueue;
    }

    /**
     * @param $recurringJob
     * @return JobInterface
     */
    protected function createJob($recurringJob): JobInterface
    {
        if (!$recurringJob instanceof $this->recurringJobClass) {
            throw new InvalidArgumentException('Recurring job must be instance of ' . $this->recurringJobClass);
        }

        $job = $this->jobFactory->createFromArray($recurringJob->{$this->jobDataAttribute});

        if ($this->jobCreatedCallback) {
            ($this->jobCreatedCallback)($job);
        }

        return $job;
    }

    /**
     * @param RecurringJob $job
     */
    public function handle(JobInterface $job): void
    {
        $query = $this->recurringJobClass::find();
        if ($this->queryModifier) {
            ($this->queryModifier)($query);
        }

        /** @var ActiveRecordRecurringJob $recurringJob */
        foreach($query->each() as $recurringJob) {
            try {
                if ($recurringJob->isDue) {
                    $this->jobQueue->putJob(
                        $this->createJob($recurringJob),
                        $this->priority,
                        $this->delay
                    );
                    $recurringJob->getBehavior(TimestampBehavior::class)->touch($this->queuedAtAttribute);
                }
            } catch (\Throwable $t) {
                \Yii::error($t);
                throw $t;
            }
        }
    }
}