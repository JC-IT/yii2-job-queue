<?php

namespace JCIT\jobqueue\migrations;

use yii\db\Migration;

class M201103160002CreateLogJobExecution extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%log_job_execution}}',
            [
                'id' => $this->primaryKey(),
                'jobExecutionId' => $this->integer()->notNull(),
                'type' => $this->string(),
                'message' => $this->text(),
                'createdAt' => $this->timestamp()->null(),
            ]
        );

        $this->addForeignKey('fk-log_job_execution-jobExecutionId-job_execution-id', '{{%log_job_execution}}', ['jobExecutionId'], '{{%job_execution}}', ['id'], 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%log_job_execution}}');
    }
}
