# Job Queue for Yii2 based on Beanstalkd

This extension provides a package that implements a queue, workers and jobs.

```bash
$ composer require jc-it/yii2-job-queue
```

or add

```
"jc-it/yii2-job-queue": "<latest version>"
```

to the `require` section of your `composer.json` file.

## Important

This package has been created to share code between projects (for now). This means that it can change regularly with breaking changes.

Make sure a specific version is used.

## Configuration

You need to have Beanstalk installed.

Configuration:

```php
'container' => [
    'definitions' => [
        \League\Tactician\CommandBus::class => function(\yii\di\Container $container) {
            return new \League\Tactician\CommandBus([
                new \League\Tactician\Handler\CommandHandlerMiddleware (
                    $container->get(\League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor::class),
                    $container->get(\League\Tactician\Handler\Locator\HandlerLocator::class),
                    $container->get(\League\Tactician\Handler\MethodNameInflector\MethodNameInflector::class)
                )
            ]);
        },
        \JCIT\jobqueue\components\ContainerMapLocator::class => function(\yii\di\Container $container) {
            $result = new \JCIT\jobqueue\components\ContainerMapLocator($container);
            // Register handlers here
            return $result;
        },
        \League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor::class => \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor::class,
        \League\Tactician\Handler\Locator\HandlerLocator::class => \JCIT\jobqueue\components\ContainerMapLocator::class,
        \JCIT\jobqueue\interfaces\JobFactoryInterface::class => \JCIT\jobqueue\factories\JobFactory::class,
        \JCIT\jobqueue\interfaces\JobQueueInterface::class => \JCIT\jobqueue\components\Beanstalk::class,
        \League\Tactician\Handler\MethodNameInflector\MethodNameInflector::class => \League\Tactician\Handler\MethodNameInflector\HandleInflector::class,
        \Pheanstalk\Contract\PheanstalkInterface::class => \JCIT\jobqueue\components\Beanstalk::class,
        \Pheanstalk\Contract\SocketFactoryInterface::class => function() {
            return new \Pheanstalk\SocketFactory('localhost', 11300, 10);
        },
    ]
],
'components' => [
    'commandBus' => \League\Tactician\CommandBus::class,
    'handlerRegistry' => \League\Tactician\Handler\Locator\HandlerLocator::class,
    'jobQueue' => \JCIT\jobqueue\interfaces\JobQueueInterface::class,
]
```

Register Daemon action in controller:

```php
public function actions(): array
{
    return [
        'daemon' => \JCIT\jobqueue\actions\DaemonAction::class,
    ];
}
```
## Credits
- [Sam Mousa](https://github.com/SamMousa)
- [Joey Claessen](https://github.com/joester89)

## License

This code is proprietary but Wolfpack IT (Works B.V.) has a forever-use right in her projects. 