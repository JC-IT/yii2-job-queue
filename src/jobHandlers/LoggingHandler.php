<?php

namespace JCIT\jobqueue\jobHandlers;

use JCIT\jobqueue\interfaces\JobHandlerInterface;
use JCIT\jobqueue\interfaces\JobHandlerLoggerInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use yii\log\Logger;

/**
 * Example of how logging can be added to the handler
 *
 * Class LogHandler
 * @package JCIT\jobqueue\jobHandlers
 */
abstract class LoggingHandler implements JobHandlerInterface
{
    /**
     * @var JobHandlerLoggerInterface
     */
    private $jobHandlerLogger;

    /**
     * LogHandler constructor.
     * @param JobHandlerLoggerInterface $jobHandlerLogger
     */
    public function __construct(JobHandlerLoggerInterface $jobHandlerLogger)
    {
        $this->jobHandlerLogger = $jobHandlerLogger;
    }

    /**
     * @param JobInterface $job
     */
    public function handle(JobInterface $job): void
    {
        try {
            $this->jobHandlerLogger->begin($job);
            $this->handleInternal($job);
        } catch (\Throwable $t) {
            $this->jobHandlerLogger->log($job, $t->getMessage(), Logger::LEVEL_ERROR);
            throw $t;
        } finally {
            $this->jobHandlerLogger->completed($job);
        }
    }

    /**
     * @param JobInterface $job
     * @return mixed
     */
    protected abstract function handleInternal(JobInterface $job);

    /**
     * @return JobHandlerLoggerInterface
     */
    protected function getJobHandlerLogger(): JobHandlerLoggerInterface
    {
        return $this->jobHandlerLogger;
    }
}