<?php

use yii\db\Migration;

/**
 * Class m230210_155341_queue_table
 */
class m230210_155341_queue_table extends Migration
{
    public $tableName = '{{%queue}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'channel'     => $this->string()->notNull(),
            'job'         => $this->binary()->notNull(),
            'pushed_at'   => $this->integer()->notNull(),
            'ttr'         => $this->integer()->notNull(),
            'delay'       => $this->integer()->notNull(),
            'priority'    => $this->integer()->unsigned()->notNull()->defaultValue(1024),
            'reserved_at' => $this->integer(),
            'attempt'     => $this->integer(),
            'done_at'     => $this->integer(),
        ], $tableOptions);
        $this->createIndex('channel', $this->tableName, 'channel');
        $this->createIndex('reserved_at', $this->tableName, 'reserved_at');
        $this->createIndex('priority', $this->tableName, 'priority');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

}
