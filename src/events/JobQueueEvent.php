<?php

namespace JCIT\jobqueue\events;

use JCIT\jobqueue\interfaces\JobInterface;
use yii\base\Event;

/**
 * Class JobQueueEvent
 * @package JCIT\jobqueue\events
 */
class JobQueueEvent extends Event
{
    public const EVENT_JOB_QUEUE_PUT = 'put';
    public const EVENT_JOB_QUEUE_HANDLE = 'handle';

    /**
     * @var JobInterface
     */
    private $job;

    /**
     * JobQueueEvent constructor.
     * @param JobInterface $job
     */
    public function __construct(JobInterface $job)
    {
        parent::__construct();
        $this->job = $job;
    }

    /**
     * @return JobInterface
     */
    public function getJob(): JobInterface
    {
        return $this->job;
    }
}