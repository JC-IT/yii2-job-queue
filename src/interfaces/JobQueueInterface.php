<?php

namespace JCIT\jobqueue\interfaces;

/**
 * Interface JobQueueInterface
 * @package JCIT\jobqueue\interfaces
 */
interface JobQueueInterface
{
    public const PRIORITY_NORMAL = 1024;
    public const PRIORITY_LOW = 4096;

    /**
     * @param JobInterface $task
     * @param int $priority
     * @param int $delay
     * @param int $ttr
     */
    public function putJob(
        JobInterface $job,
        int $priority = self::PRIORITY_NORMAL,
        int $delay = 0,
        int $ttr = 60
    ): void;
}