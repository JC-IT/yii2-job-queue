<?php

namespace JCIT\jobqueue\interfaces;

interface JobFactoryInterface
{
    public function createFromArray(array $data): JobInterface;
    public function createFromJson(string $data): JobInterface;
    public function saveToArray(JobInterface $job): array;
    public function saveToJson(JobInterface $job): string;
}
