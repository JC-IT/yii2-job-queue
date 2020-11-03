<?php

namespace JCIT\jobqueue\actions;

use JCIT\jobqueue\jobs\RecurringJob;
use League\Tactician\CommandBus;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\console\Application;

/**
 * Class CronAction
 * @package JCIT\jobqueue\actions
 */
class RecurringJobAction extends Action
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * DaemonAction constructor.
     * @param $id
     * @param $controller
     * @param CommandBus $commandBus
     * @param array $config
     */
    public function __construct(
        $id,
        $controller,
        CommandBus $commandBus,
        $config = []
    ) {
        $this->commandBus = $commandBus;

        parent::__construct($id, $controller, $config);
    }

    public function init()
    {
        if (!$this->controller->module instanceof Application) {
            throw new InvalidConfigException('This action can only be used in a console application.');
        }

        parent::init();
    }

    public function run()
    {
        while(true) {
            try {
                $this->commandBus->handle(new RecurringJob());
                sleep(60);
            } catch (\Throwable $throwable) {
                \Yii::error($throwable);
                sleep(60);
            }
        }
    }
}