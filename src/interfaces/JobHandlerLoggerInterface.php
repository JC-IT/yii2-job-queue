<?php

namespace JCIT\jobqueue\interfaces;

use yii\log\Logger;

interface JobHandlerLoggerInterface
{
    public function begin(JobInterface $job): void;
    public function completed(JobInterface $job): void;
    public function failed(JobInterface $job): void;
    public function log(JobInterface $job, string $message = '', int $level = Logger::LEVEL_INFO): void;
}
