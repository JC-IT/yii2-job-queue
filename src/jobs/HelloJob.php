<?php

namespace JCIT\jobqueue\jobs;

use JCIT\jobqueue\interfaces\JobInterface;

class HelloJob implements JobInterface
{
    public function __construct(
        private string $name
    ) {
    }

    public static function fromArray(array $config): JobInterface
    {
        return new static($config['name']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
