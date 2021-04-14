<?php

namespace JCIT\jobqueue\interfaces;

use JsonSerializable;

interface JobInterface extends JsonSerializable
{
    public static function fromArray(array $config): JobInterface;
}
