<?php

namespace JCIT\jobqueue\factories;

use InvalidArgumentException;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobInterface;
use JsonException;

/**
 * Class JobFactory
 * @package JCIT\jobqueue\factories
 */
class JobFactory implements JobFactoryInterface
{
    /**
     * @param array $data
     * @return JobInterface
     * @throws JsonException
     */
    public function createFromArray(array $data): JobInterface
    {
        if (!isset($data['class'], $data['data'])) {
            throw new InvalidArgumentException('Data does not contain required class key');
        }

        if (!class_exists($data['class'])) {
            throw new InvalidArgumentException("Unknown class '{$data['class']}' given");
        }

        if (!is_subclass_of($data['class'], JobInterface::class)) {
            throw new InvalidArgumentException("Class '{$data['class']}' does not implement JobInterface");
        }

        return $data['class']::fromArray($data['data']);
    }

    /**
     * @param string $data
     * @return JobInterface
     * @throws JsonException
     */
    public function createFromJson(string $data): JobInterface
    {
        return $this->createFromArray(json_decode($data, true, 512));
    }

    /**
     * @param JobInterface $job
     * @return array
     */
    public function saveToArray(JobInterface $job): array
    {
        return [
            'class' => get_class($job),
            'data' => $job->jsonSerialize(),
        ];
    }

    /**
     * @param JobInterface $job
     * @return string
     */
    public function saveToJson(JobInterface $job): string
    {
        return json_encode($this->saveToArray($job), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
