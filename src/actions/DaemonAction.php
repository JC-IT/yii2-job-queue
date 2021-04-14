<?php
declare(strict_types=1);

namespace JCIT\jobqueue\actions;

use JCIT\jobqueue\events\JobQueueEvent;
use JCIT\jobqueue\exceptions\PermanentException;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use League\Tactician\CommandBus;
use Pheanstalk\Contract\PheanstalkInterface;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\console\Application;
use yii\db\Connection;
use yii\helpers\Console;

class DaemonAction extends Action
{
    public int $reserveWithTimeout = 120;

    public function __construct(
        $id,
        $controller,
        private PheanstalkInterface $beanstalk,
        private CommandBus $commandBus,
        private Connection $db,
        private JobFactoryInterface $jobFactory,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
    }

    public function init(): void
    {
        if (!$this->controller->module instanceof Application) {
            throw new InvalidConfigException('This action can only be used in a console application.');
        }

        parent::init();
    }

    public function run(
        $reserveWithTimeout = null
    ): void {
        $reserveWithTimeout = $reserveWithTimeout ?? $this->reserveWithTimeout;

        $this->controller->stdout("Waiting for jobs" . PHP_EOL, Console::FG_CYAN);

        while (true) {
            $this->controller->stdout('.', Console::FG_CYAN);
            $job = $this->beanstalk->reserveWithTimeout($reserveWithTimeout);
            if (isset($job)) {
                try {
                    $jobCommand = $this->jobFactory->createFromJson($job->getData());
                    $event = new JobQueueEvent($jobCommand);
                    \Yii::$app->trigger($event::EVENT_JOB_QUEUE_HANDLE, $event);

                    $jobClass = get_class($jobCommand);
                    $this->controller->stdout(PHP_EOL . "Starting job: {$jobClass}({$job->getId()})" . PHP_EOL, Console::FG_CYAN);
                    $this->commandBus->handle($jobCommand);
                    $this->controller->stdout("Deleting job: {$job->getId()}" . PHP_EOL, Console::FG_GREEN);
                    $this->beanstalk->delete($job);
                } catch (PermanentException $e) {
                    \Yii::error($e, self::class);
                    $this->controller->stdout(PHP_EOL . "Deleting job({$job->getId()}) with permanent exception: {$e->getMessage()}" . PHP_EOL, Console::FG_RED);
                    $this->beanstalk->delete($job);
                } catch (\Throwable $t) {
                    \Yii::error($t, self::class);
                    $this->controller->stdout(PHP_EOL . "Burying job({$job->getId()}) with message: {$t->getMessage()}" . PHP_EOL, Console::FG_YELLOW);
                    $this->beanstalk->bury($job);
                }
            }
            
            $this->db->close();

            \Yii::getLogger()->flush();
            foreach (\Yii::getLogger()->dispatcher->targets as $target) {
                $target->export();
            }
        }
    }
}
