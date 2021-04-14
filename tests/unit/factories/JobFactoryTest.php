<?php
declare(strict_types=1);

namespace JCIT\jobqueue\tests\unit\factories;

use Codeception\Test\Unit;
use InvalidArgumentException;
use JCIT\jobqueue\factories\JobFactory;
use JCIT\jobqueue\jobs\HelloJob;

class JobFactoryTest extends Unit
{
    public function testFromArrayMissingKeys(): void
    {
        $data = [];
        $this->expectException(InvalidArgumentException::class);
        $jobFactory = new JobFactory();
        $jobFactory->createFromArray($data);
    }

    public function testFromArrayUnknownClass(): void
    {
        $data = ['class' => 'HelloJob', 'data' => []];
        $this->expectException(InvalidArgumentException::class);
        $jobFactory = new JobFactory();
        $jobFactory->createFromArray($data);
    }

    public function testFromArrayNoJob(): void
    {
        $data = ['class' => JobFactory::class, 'data' => []];
        $this->expectException(InvalidArgumentException::class);
        $jobFactory = new JobFactory();
        $jobFactory->createFromArray($data);
    }

    public function testViaArray(): void
    {
        $name = 'Test';
        $job = new HelloJob($name);

        $jobFactory = new JobFactory();
        $this->assertEquals($name, $jobFactory->createFromArray($jobFactory->saveToArray($job))->getName());
    }

    public function testViaJson(): void
    {
        $name = 'Test';
        $job = new HelloJob($name);

        $jobFactory = new JobFactory();
        $this->assertEquals($name, $jobFactory->createFromJson($jobFactory->saveToJson($job))->getName());
    }
}
