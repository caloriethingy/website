<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UnauthorizedHttpException;

/**
 * This is the model class for table "meal".
 *
 * @property int $id
 * @property string $file_name
 * @property string $context
 * @property string $food_name
 * @property string $type
 * @property int $calories
 * @property int $protein
 * @property int $fat
 * @property int $carbohydrates
 * @property int $fiber
 * @property int $user_id
 * @property int $created_at
 * @property int $updated_at
 */
class Meal extends ActiveRecord
{

    public $base64File;
    public const BREAKFAST = 'breakfast';
    public const LUNCH     = 'lunch';
    public const DINNER    = 'dinner';
    public const OTHER     = 'other';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meal';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'food_name', 'calories', 'protein', 'fat', 'carbohydrates', 'fiber', 'type'], 'required'],
            [['user_id', 'calories', 'protein', 'fat', 'carbohydrates', 'fiber', 'created_at', 'updated_at'], 'integer'],
            [['date'], 'date'],
            [['type', 'context'], 'string'],
            [['type'], 'in', 'range' => [self::BREAKFAST, self::LUNCH, self::DINNER, self::OTHER]],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    // Automatically set the user_id before saving a new meal
    /**
     * @throws UnauthorizedHttpException
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            // Get the currently authenticated user's ID
            if (Yii::$app->user->isGuest) {
                throw new \yii\web\UnauthorizedHttpException('User not authenticated.');
            }

            // Set the user_id to the current authenticated user
            $this->user_id = Yii::$app->user->identity->id;
        }

        return parent::beforeSave($insert);
    }

    // Define relationships (if any)
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
