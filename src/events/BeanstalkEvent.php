<?php

namespace JCIT\jobqueue\events;

use Pheanstalk\Job;
use yii\base\Event;

/**
 * Class BeanstalkEvent
 * @package JCIT\jobqueue\events
 */
class BeanstalkEvent extends Event
{
    public const EVENT_JOB_SUBMITTED = 'putjob';

    /**
     * @var Job
     */
    private $job;

    /**
     * BeanstalkEvent constructor.
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        parent::__construct();
        $this->job = $job;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }
}