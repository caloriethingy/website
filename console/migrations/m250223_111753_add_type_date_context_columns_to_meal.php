<?php

use yii\db\Migration;

class m250223_111753_add_type_date_context_columns_to_meal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%meal}}', 'type', $this->string()->null()); // nullable because existing user data
        $this->addColumn('{{%meal}}', 'date', $this->date()->null()); // nullable because existing user data
        $this->addColumn('{{%meal}}', 'context', $this->string(100)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%meal}}', 'type');
        $this->dropColumn('{{%meal}}', 'date');
        $this->dropColumn('{{%meal}}', 'context');
    }

}
