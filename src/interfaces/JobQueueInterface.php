<?php
declare(strict_types=1);

namespace JCIT\jobqueue\interfaces;

interface JobQueueInterface
{
    public const int PRIORITY_NORMAL = 1024;
    public const int PRIORITY_LOW = 4096;

    public function putJob(
        JobInterface $job,
        int $priority = self::PRIORITY_NORMAL,
        int $delay = 0,
        int $ttr = 60
    ): void;
}
