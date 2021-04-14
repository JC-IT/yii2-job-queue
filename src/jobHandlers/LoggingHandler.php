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
    public function __construct(
        private JobHandlerLoggerInterface $jobHandlerLogger
    ) {
    }

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

    protected function getJobHandlerLogger(): JobHandlerLoggerInterface
    {
        return $this->jobHandlerLogger;
    }

    protected abstract function handleInternal(JobInterface $job): void;
}
