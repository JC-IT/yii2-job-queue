<?php

namespace JCIT\jobqueue\interfaces;

use JsonSerializable;
use Pheanstalk\Contract\JobIdInterface;

/**
 * Interface JobInterface
 * @package JCIT\jobqueue\interfaces
 */
interface JobInterface extends JsonSerializable, JobIdInterface
{
    /**
     * @param array $config
     * @return JobInterface
     */
    public static function fromArray(array $config): JobInterface;
}
