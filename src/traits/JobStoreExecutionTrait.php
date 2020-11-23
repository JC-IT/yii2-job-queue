<?php

namespace JCIT\jobqueue\traits;

use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobStoreExecutionInterface;
use JCIT\jobqueue\models\activeRecord\JobExecution;

/**
 * Trait JobStoreExecutionTrait
 * @package JCIT\jobqueue\traits
 */
trait JobStoreExecutionTrait
{
    /**
     * @var string
     */
    protected $jobExecutionClass = JobExecution::class;

    /**
     * @var int
     */
    protected $jobExecutionId;

    /**
     * JobStoreExecutionTrait constructor.
     * @param int|null $jobExecutionId
     */
    public function __construct(int $jobExecutionId = null)
    {
        $this->jobExecutionId = $jobExecutionId;
    }

    /**
     * @return JobExecution
     */
    public function getJobExecution(): JobExecution
    {
        if ($this->jobExecutionId) {
            return $this->jobExecutionClass::findOne(['id' => $this->jobExecutionId]);
        } else {
            $jobFactory = \Yii::createObject(JobFactoryInterface::class);
            $jobExecution = new JobExecution([
                'status' => JobExecution::STATUS_CREATED,
                'jobData' => $jobFactory->saveToArray($this),
            ]);
            $jobExecution->save();
            $this->jobExecutionId = $jobExecution->id;
            return $jobExecution;
        }
    }

    /**
     * @param JobExecution $jobExecution
     * @return $this
     */
    public function setJobExecution(JobExecution $jobExecution): JobStoreExecutionInterface
    {
        $this->jobExecutionId = $jobExecution->id;
        return $this;
    }
}
