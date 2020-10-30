<?php

namespace JCIT\jobqueue\actions;

use JCIT\jobqueue\exceptions\PermanentException;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use League\Tactician\CommandBus;
use Pheanstalk\Contract\PheanstalkInterface;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\console\Application;
use yii\db\Connection;
use yii\helpers\Console;

/**
 * Class DaemonAction
 * @package JCIT\jobqueue\actions
 */
class DaemonAction extends Action
{
    /**
     * @var PheanstalkInterface
     */
    protected $beanstalk;

    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var JobFactoryInterface
     */
    protected $jobFactory;

    /**
     * @var int
     */
    public $reserveWithTimeout = 120;

    /**
     * DaemonAction constructor.
     * @param $id
     * @param $controller
     * @param PheanstalkInterface $beanstalk
     * @param CommandBus $commandBus
     * @param Connection $db
     * @param JobFactoryInterface $jobFactory
     * @param array $config
     */
    public function __construct(
        $id,
        $controller,
        PheanstalkInterface $beanstalk,
        CommandBus $commandBus,
        Connection $db,
        JobFactoryInterface $jobFactory,
        $config = []
    ) {
        $this->beanstalk = $beanstalk;
        $this->commandBus = $commandBus;
        $this->db = $db;
        $this->jobFactory = $jobFactory;

        parent::__construct($id, $controller, $config);
    }

    public function init()
    {
        if (!$this->controller->module instanceof Application) {
            throw new InvalidConfigException('This action can only be used in a console application.');
        }

        parent::init();
    }

    public function run(
        $reserveWithTimeout = null
    ) {
        $reserveWithTimeout = $reserveWithTimeout ?? $this->reserveWithTimeout;

        $this->controller->stdout("Waiting for jobs\n", Console::FG_CYAN);

        while(true) {
            $job = $this->beanstalk->reserveWithTimeout($reserveWithTimeout);
            $this->controller->stdout('.', Console::FG_CYAN);
            if (isset($job)) {
                try {
                    $job = $this->jobFactory->createFromJson($job->getData());

                    $this->commandBus->handle($job);
                    $this->controller->stdout("Deleting job: {$job->getId()}" . PHP_EOL, Console::FG_GREEN);
                    $this->beanstalk->delete($job);
                } catch (PermanentException $e) {
                    \Yii::error($e, self::class);
                    $this->controller->stdout("Deleting job with permanent exception: {$job->getId()}" . PHP_EOL, Console::FG_RED);
                    $this->beanstalk->delete($job);
                } catch (\Throwable $t) {
                    \Yii::error($t, self::class);
                    $this->controller->stdout("Burying job: {$job->getId()}" . PHP_EOL, Console::FG_YELLOW);
                    $this->beanstalk->bury($job);
                }

                $this->db->close();
            }

            \Yii::getLogger()->flush();
            foreach(\Yii::getLogger()->dispatcher->targets as $target) {
                $target->export();
            }
        }
    }
}