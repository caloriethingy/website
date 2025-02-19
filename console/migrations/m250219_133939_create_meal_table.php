<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%meal}}`.
 */
class m250219_133939_create_meal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%meal}}', [
            'id' => $this->primaryKey(),
            'file_name' => $this->string()->notNull(),
            'food_name' => $this->string()->notNull(),
            'calories' => $this->integer()->notNull(),
            'protein' => $this->integer()->notNull(),
            'fat' => $this->integer()->notNull(),
            'carbohydrates' => $this->integer()->notNull(),
            'fiber' => $this->integer()->notNull(),
            'meal' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%meal}}');
    }
}
