<?php

namespace JCIT\jobqueue\migrations;

use yii\db\Migration;

/**
 * Class M201103160000CreateRecurringJob
 * @package JCIT\jobqueue\migrations
 */
class M201103160000CreateRecurringJob extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            '{{%recurring_job}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'description' => $this->text(),
                'cron' => $this->string()->notNull(),
                'taskData' => $this->json(),

                'queued_at' => $this->timestamp()->null(),
                'created_at' => $this->timestamp()->null(),
                'updated_at' => $this->timestamp()->null(),
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%recurring_job}}');
    }
}