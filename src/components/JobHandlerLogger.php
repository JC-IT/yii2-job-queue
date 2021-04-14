<?php

namespace JCIT\jobqueue\components;

use JCIT\jobqueue\interfaces\JobHandlerLoggerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobStoreExecutionInterface;
use JCIT\jobqueue\models\activeRecord\JobExecution;
use JCIT\jobqueue\models\activeRecord\LogJobExecution;
use yii\log\Logger;

class JobHandlerLogger implements JobHandlerLoggerInterface
{
    public function begin(JobInterface $job): void
    {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();
            $jobExecution->status = JobExecution::STATUS_STARTED;
            $jobExecution->save(true, ['status']);
            $jobExecution->touch('startedAt');
        }
    }

    public function completed(JobInterface $job): void
    {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();
            $jobExecution->status = JobExecution::STATUS_COMPLETED;
            $jobExecution->save(true, ['status']);
            $jobExecution->touch('endedAt');
        }
    }

    public function failed(JobInterface $job): void
    {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();
            $jobExecution->status = JobExecution::STATUS_FAILED;
            $jobExecution->save(true, ['status']);
            $jobExecution->touch('endedAt');
        }
    }

    public function log(
        JobInterface $job,
        string $message = '',
        $level = Logger::LEVEL_INFO
    ): void {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();

            $logJobExecution = new LogJobExecution([
                'jobExecutionId' => $jobExecution->id,
                'message' => $message,
                'type' => (string) $level,
            ]);
            $logJobExecution->save();
        }
    }
}
