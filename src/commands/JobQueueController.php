<?php

namespace JCIT\jobqueue\commands;

use Pheanstalk\Contract\PheanstalkInterface;
use yii\console\Controller;
use yii\db\Connection;
use yii\helpers\Console;

/**
 * Class JobQueueController
 * @package JCIT\jobqueue\commands
 */
class JobQueueController extends Controller
{
    public function actionDaemon(
        Connection $db,
        PheanstalkInterface $beanstalk,
        CommandBus $commandBus
    ) {
        $this->stdout("Waiting for jobs\n", Console::FG_CYAN);
        $taskFactory = new TaskFactory();
        while(true) {
            $job = $beanstalk->reserveWithTimeout(120);
            $this->stdout('.', Console::FG_CYAN);
            if (isset($job)) {
                try {
                    /** @var HookTask $task */
                    $task = $taskFactory->createFromJson($job->getData());

                    $commandBus->handle($task);
                    $this->stdout("Deleting job: {$job->getId()}" . PHP_EOL, Console::FG_GREEN);
                    $beanstalk->delete($job);
                } catch (PermanentException $e) {
                    \Yii::error($e, 'queuerunner');
                    $this->stdout("Deleting job with permanent exception: {$job->getId()}" . PHP_EOL, Console::FG_RED);
                    $beanstalk->delete($job);
                } catch (\Throwable $t) {
                    \Yii::error($t, 'queuerunner');
                    $this->stdout("Burying job: {$job->getId()}" . PHP_EOL, Console::FG_YELLOW);
                    $beanstalk->bury($job);
                }

                $db->close();
            }
            \Yii::getLogger()->flush();
            foreach(\Yii::getLogger()->dispatcher->targets as $target) {
                $target->export();
            }

        }
    }
}