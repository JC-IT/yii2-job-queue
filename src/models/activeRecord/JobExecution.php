<?php

namespace JCIT\jobqueue\models\activeRecord;

use JCIT\jobqueue\interfaces\JobFactoryInterface;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\validators\ExistValidator;
use yii\validators\InlineValidator;
use yii\validators\RangeValidator;
use yii\validators\RequiredValidator;

/**
 * Class JobExecution
 * @package JCIT\jobqueue\models\activeRecord
 *
 * @property int $id [int(11)]
 * @property int $recurringJobId [int(11)]
 * @property array $jobData [json]
 * @property string $status [varchar(255)]
 * @property int $createdBy [int(11)]
 * @property int $createdAt [timestamp]
 * @property int $updatedAt [timestamp]
 *
 * @method void touch($attribute)
 */
class JobExecution extends ActiveRecord
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_CREATED = 'created';
    const STATUS_FAILED = 'failed';
    const STATUS_QUEUED = 'queued';
    const STATUS_STARTED = 'started';

    /**
     * @var string
     */
    protected $recurringJobClass = RecurringJob::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            BlameableBehavior::class => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getRecurringJob(): ActiveQuery
    {
        return $this->hasOne($this->recurringJobClass, ['id' => 'recurringJobId']);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['status', 'jobData'], RequiredValidator::class],
            [['status'], RangeValidator::class, 'range' => array_keys($this->statusOptions())],
            [['recurringJobId'], ExistValidator::class, 'targetRelation' => 'recurringJob'],
            [['jobData'], function($attribute, $params, InlineValidator $validator){
                try {
                    \Yii::createObject(JobFactoryInterface::class)->createFromArray($this->jobData);
                } catch (\Throwable $t) {
                    $this->addError($attribute, $t->getMessage());
                }
            }]
        ];
    }

    /**
     * @return array
     */
    public function statusOptions(): array
    {
        return [
            self::STATUS_CREATED => \Yii::t('app', 'Created'),
            self::STATUS_FAILED => \Yii::t('app', 'Failed'),
            self::STATUS_QUEUED => \Yii::t('app', 'Queued'),
            self::STATUS_STARTED => \Yii::t('app', 'Started'),
            self::STATUS_COMPLETED => \Yii::t('app', 'Successful'),
        ];
    }
}