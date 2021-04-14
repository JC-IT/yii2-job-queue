<?php
declare(strict_types=1);

namespace JCIT\jobqueue\interfaces;

use JsonSerializable;

interface JobInterface extends JsonSerializable
{
    public static function fromArray(array $config): JobInterface;
}
