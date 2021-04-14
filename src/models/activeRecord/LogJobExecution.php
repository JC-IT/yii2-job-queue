<?php

namespace JCIT\jobqueue\models\activeRecord;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\validators\ExistValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;

/**
 * @property int $id [int(11)]
 * @property int $jobExecutionId [int(11)]
 * @property string $type [varchar(255)]
 * @property string $message
 * @property int $createdAt [timestamp]
 *
 * @property-read JobExecution $jobExecution
 */
class LogJobExecution extends ActiveRecord
{
    protected string $jobExecutionClass = JobExecution::class;

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getJobExecution(): ActiveQuery
    {
        return $this->hasOne($this->jobExecutionClass, ['id' => 'jobExecutionId']);
    }

    public function rules(): array
    {
        return [
            [['jobExecutionId', 'message'], RequiredValidator::class],
            [['type', 'message'], StringValidator::class],
            [['jobExecutionId'], ExistValidator::class, 'targetRelation' => 'jobExecution'],
        ];
    }
}
