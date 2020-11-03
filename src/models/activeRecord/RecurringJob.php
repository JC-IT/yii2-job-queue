<?php

namespace JCIT\jobqueue\models\activeRecord;

use Cron\CronExpression;
use JCIT\jobqueue\interfaces\JobFactoryInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\validators\RequiredValidator;

/**
 * Class RecurringJob
 * @package JCIT\jobqueue
 *
 * @property int $id [int(11)]
 * @property string $name [varchar(255)]
 * @property string $description
 * @property string $cron
 * @property array $task_data [json]
 * @property int|null $queued_at [timestamp]
 * @property int|null $created_at [timestamp]
 * @property int|null $updated_at [timestamp]
 *
 * @property-read bool $isDue
 */
class RecurringJob extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @return bool
     */
    public function getIsDue(): bool
    {
        return CronExpression::factory($this->cron)->isDue();
    }

    /**
     * @return array|array[]
     */
    public function rules(): array
    {
        return [
            [['name', 'cron', 'taskConfig'], RequiredValidator::class],
            [['cron'], function() {
                try {
                    CronExpression::factory($this->cron);
                } catch (\InvalidArgumentException $e) {
                    $this->addError('cron', $e->getMessage());
                }
            }],

            [['taskConfig'], function(){
                try {
                    \Yii::createObject(JobFactoryInterface::class)->createFromArray($this->task_data);
                } catch (\Throwable $t) {
                    $this->addError('taskConfig', $t->getMessage());
                }
            }]
        ];
    }
}
