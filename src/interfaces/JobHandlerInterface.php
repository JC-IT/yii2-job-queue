<?php

namespace JCIT\jobqueue\interfaces;

use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

/**
 * This is the handler interface if the @see MethodNameInflector
 * is implemented by @see HandleInflector
 */
interface JobHandlerInterface
{
    public function handle(JobInterface $job): void;
}
