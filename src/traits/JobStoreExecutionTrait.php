<?php

namespace JCIT\jobqueue\traits;

use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobStoreExecutionInterface;
use JCIT\jobqueue\models\activeRecord\JobExecution;

trait JobStoreExecutionTrait
{
    /** @var class-string */
    protected string $jobExecutionClass = JobExecution::class;

    public function __construct(
        private ?int $jobExecutionId = null
    ) {
    }

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

    public function setJobExecution(JobExecution $jobExecution): JobStoreExecutionInterface
    {
        $this->jobExecutionId = $jobExecution->id;
        return $this;
    }
}
