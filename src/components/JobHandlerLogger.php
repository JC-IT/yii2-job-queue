<?php

namespace JCIT\jobqueue\components;

use JCIT\jobqueue\interfaces\JobHandlerLoggerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JCIT\jobqueue\interfaces\JobStoreExecutionInterface;
use JCIT\jobqueue\models\activeRecord\JobExecution;
use JCIT\jobqueue\models\activeRecord\LogJobExecution;
use yii\behaviors\TimestampBehavior;
use yii\log\Logger;

/**
 * Class JobHandlerLogger
 * @package common\components
 */
class JobHandlerLogger implements JobHandlerLoggerInterface
{
    /**
     * @var JobExecution
     */
    protected $jobExcecution;

    /**
     * @param JobInterface $job
     */
    public function begin(JobInterface $job): void
    {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();
            $jobExecution->status = JobExecution::STATUS_STARTED;
            $jobExecution->save(true, ['status']);
            $jobExecution->touch('startedAt');
        }
    }

    /**
     * @param JobInterface $job
     */
    public function completed(JobInterface $job): void
    {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();
            $jobExecution->status = JobExecution::STATUS_COMPLETED;
            $jobExecution->save(true, ['status']);
            $jobExecution->touch('endedAt');
        }
    }

    /**
     * @param JobInterface $job
     */
    public function failed(JobInterface $job): void
    {
        if ($job instanceof JobStoreExecutionInterface) {
            $jobExecution = $job->getJobExecution();
            $jobExecution->status = JobExecution::STATUS_FAILED;
            $jobExecution->save(true, ['status']);
            $jobExecution->touch('endedAt');
        }
    }

    /**
     * @param JobInterface $job
     * @param string $message
     * @param $level
     */
    public function log(JobInterface $job, $message = '', $level = Logger::LEVEL_INFO): void
    {
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
