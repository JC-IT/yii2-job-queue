<?php

namespace JCIT\jobqueue\jobs;

use JCIT\jobqueue\interfaces\JobInterface;

/**
 * Class RecurringJob
 * @package JCIT\jobqueue\jobs
 */
class RecurringJob implements JobInterface
{
    /**
     * @param array $config
     * @return JobInterface
     */
    public static function fromArray(array $config): JobInterface
    {
        return new static;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [];
    }
}