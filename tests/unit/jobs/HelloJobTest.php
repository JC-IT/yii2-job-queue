<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\jobs;

use Codeception\Test\Unit;
use JCIT\jobqueue\jobs\HelloJob;

class HelloJobTest extends Unit
{
    public function testSerialize(): void
    {
        $name = 'Test';
        $job = new HelloJob($name);

        $this->assertEquals(['name' => $name], $job->jsonSerialize());
    }

    public function testDeserialize(): void
    {
        $name = 'Test';
        $serialization = ['name' => $name];

        $job = HelloJob::fromArray($serialization);
        $this->assertEquals($name, $job->getName());
    }
}
