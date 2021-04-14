<?php

namespace JCIT\jobqueue\migrations;

use yii\db\Migration;

class M201103160001CreateJobExecution extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%job_execution}}',
            [
                'id' => $this->primaryKey(),
                'recurringJobId' => $this->integer(),
                'jobData' => $this->json(),
                'status' => $this->string()->notNull(),

                'createdBy' => $this->integer(),
                'createdAt' => $this->timestamp()->null(),
                'updatedAt' => $this->timestamp()->null(),
                'queuedAt' => $this->timestamp()->null(),
                'startedAt' => $this->timestamp()->null(),
                'endedAt' => $this->timestamp()->null(),
            ]
        );

        $this->addForeignKey('fk-job_execution-recurringJobId-recurring_job-id', '{{%job_execution}}', ['recurringJobId'], '{{%recurring_job}}', ['id'], 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('{{%job_execution}}');
    }
}
