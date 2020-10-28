<?php

namespace JCIT\jobqueue\interfaces;

/**
 * Interface JobFactoryInterface
 * @package JCIT\jobqueue\interfaces
 */
interface JobFactoryInterface
{
    /**
     * @param array $data
     * @return JobInterface
     */
    public function createFromArray(array $data): JobInterface;

    /**
     * @param string $data
     * @return JobInterface
     */
    public function createFromJson(string $data): JobInterface;

    /**
     * @param JobInterface $job
     * @return string
     */
    public function saveToJson(JobInterface $job): string;
}