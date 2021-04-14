<?php

namespace JCIT\jobqueue\jobHandlers;

use JCIT\jobqueue\interfaces\JobHandlerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\jobs\HelloJob;

class HelloHandler implements JobHandlerInterface
{
    /**
     * @param HelloJob $job
     */
    public function handle(JobInterface $job): void
    {
        echo 'Hello ' . $job->getName();
    }
}
