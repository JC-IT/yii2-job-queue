<?php

namespace JCIT\jobqueue\interfaces;

use JsonSerializable;

/**
 * Interface JobInterface
 * @package JCIT\jobqueue\interfaces
 */
interface JobInterface extends JsonSerializable
{
    /**
     * @param array $config
     * @return JobInterface
     */
    public static function fromArray(array $config): JobInterface;
}
