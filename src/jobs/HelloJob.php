<?php

namespace JCIT\jobqueue\jobs;

use JCIT\jobqueue\interfaces\JobInterface;

/**
 * Class HelloJob
 * @package JCIT\jobqueue\jobHandlers
 */
class HelloJob implements JobInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * HelloJob constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param array $config
     * @return JobInterface
     */
    public static function fromArray(array $config): JobInterface
    {
        return new static($config['name']);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}