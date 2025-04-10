<?php
declare(strict_types=1);

namespace JCIT\jobqueue\events;

use JCIT\jobqueue\interfaces\JobInterface;
use yii\base\Event;

class JobQueueEvent extends Event
{
    public const string EVENT_JOB_QUEUE_PUT = 'put';
    public const string EVENT_JOB_QUEUE_HANDLE = 'handle';

    public function __construct(
        private readonly JobInterface $job
    ) {
        parent::__construct();
    }

    public function getJob(): JobInterface
    {
        return $this->job;
    }
}
