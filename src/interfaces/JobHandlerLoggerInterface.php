<?php

namespace JCIT\jobqueue\interfaces;

use yii\log\Logger;

/**
 * Interface JobHandlerLoggerInterface
 * @package JCIT\jobqueue\interfaces
 */
interface JobHandlerLoggerInterface
{
    /**
     * @param JobInterface $job
     */
    public function begin(JobInterface $job): void;

    /**
     * @param JobInterface $job
     */
    public function completed(JobInterface $job): void;

    /**
     * @param JobInterface $job
     * @param string $message
     * @param int $level
     */
    public function log(JobInterface $job, $message = '', $level = Logger::LEVEL_INFO): void;
}