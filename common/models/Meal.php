<?php

namespace common\models;

/**
 * This is the model class for table "meal".
 *
 * @property int $id
 * @property string $file_name
 * @property int $calories
 * @property int $protein
 * @property int $fat
 * @property int $carbohydrates
 * @property int $fiber
 * @property int $meal
 * @property int $created_at
 * @property int $updated_at
 */
class Meal extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_name', 'calories', 'protein', 'fat', 'carbohydrates', 'fiber', 'meal', 'created_at', 'updated_at'], 'required'],
            [['calories', 'protein', 'fat', 'carbohydrates', 'fiber', 'meal', 'created_at', 'updated_at'], 'integer'],
            [['file_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'File Name',
            'calories' => 'Calories',
            'protein' => 'Protein',
            'fat' => 'Fat',
            'carbohydrates' => 'Carbohydrates',
            'fiber' => 'Fiber',
            'meal' => 'Meal',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
