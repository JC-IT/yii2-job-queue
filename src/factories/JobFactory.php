<?php
declare(strict_types=1);

namespace JCIT\jobqueue\factories;

use InvalidArgumentException;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use JCIT\jobqueue\interfaces\JobInterface;

class JobFactory implements JobFactoryInterface
{
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

    public function createFromJson(string $data): JobInterface
    {
        return $this->createFromArray(json_decode($data, true));
    }

    public function saveToArray(JobInterface $job): array
    {
        return [
            'class' => get_class($job),
            'data' => $job->jsonSerialize(),
        ];
    }

    public function saveToJson(JobInterface $job): string
    {
        return json_encode($this->saveToArray($job), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
