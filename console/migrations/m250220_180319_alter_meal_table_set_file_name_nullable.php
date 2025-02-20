<?php

use yii\db\Migration;

class m250220_180319_alter_meal_table_set_file_name_nullable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%meal}}', 'file_name', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250220_180319_alter_meal_table_set_file_name_nullable cannot be reverted.\n";

        return false;
    }

}
