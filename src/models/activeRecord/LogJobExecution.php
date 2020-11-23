<?php

namespace JCIT\jobqueue\models\activeRecord;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\validators\ExistValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * Class LogJobExecution
 * @package JCIT\jobqueue\models\activeRecord
 *
 * @property int $id [int(11)]
 * @property int $jobExecutionId [int(11)]
 * @property string $type [varchar(255)]
 * @property string $message
 * @property int $createdAt [timestamp]
 */
class LogJobExecution extends ActiveRecord
{
    /**
     * @var string
     */
    protected $jobExecutionClass = JobExecution::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getJobExecution(): ActiveQuery
    {
        return $this->hasOne($this->jobExecutionClass, ['id' => 'jobExecutionId']);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['jobExecutionId', 'message'], RequiredValidator::class],
            [['type', 'message'], StringValidator::class],
            [['jobExecutionId'], ExistValidator::class, 'targetRelation' => 'jobExecution'],
        ];
    }
}