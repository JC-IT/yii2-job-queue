<?php
declare(strict_types=1);

namespace JCIT\jobqueue\events;

use JCIT\jobqueue\interfaces\JobInterface;
use yii\base\Event;

class JobQueueEvent extends Event
{
    public const EVENT_JOB_QUEUE_PUT = 'put';
    public const EVENT_JOB_QUEUE_HANDLE = 'handle';

    /**
     * JobQueueEvent constructor.
     * @param JobInterface $job
     */
    public function __construct(
        private JobInterface $job
    ) {
        parent::__construct();
    }

    public function getJob(): JobInterface
    {
        return $this->job;
    }
}
