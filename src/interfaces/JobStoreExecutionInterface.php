<?php

namespace JCIT\jobqueue\interfaces;

use JCIT\jobqueue\models\activeRecord\JobExecution;

/**
 * Interface JobStoreExecutionInterface
 * @package common\interfaces
 */
interface JobStoreExecutionInterface extends JobInterface
{
    /**
     * @return JobExecution
     */
    public function getJobExecution(): JobExecution;

    /**
     * @param JobExecution $jobExecution
     * @return $this
     */
    public function setJobExecution(JobExecution $jobExecution): self;
}