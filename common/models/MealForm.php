<?php

namespace common\models;

use DateInterval;
use DateTime;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class MealForm extends Model
{

    public $context;
    /**
     * @var UploadedFile
     */
    public $picture;
    public string $filepath;
    public $date;
    public int $day = 0;
    public $type = Meal::OTHER; // type of meal - default to other

    public function init()
    {
        // @todo get user timezone to determine - should depend on their location not their settings
        $hour = (int) (new DateTime())->format('H');
        if ($hour >= 6 && $hour < 11) { // Breakfast time
            $this->type = Meal::BREAKFAST;
        } elseif ($hour >= 11 && $hour < 15) { // Lunch time
            $this->type = Meal::LUNCH;
        } elseif ($hour >= 15 && $hour < 21) { // Dinner time
            $this->type = Meal::DINNER;
        }
    }


    public function rules()
    {
        return [
            [['picture'], 'image', 'skipOnEmpty' => false],
            [['picture', 'day', 'type'], 'required'],
            [['type'], 'string'],
            [['day'], 'integer'],
            [['context'], 'string', 'length' => [0, 100]],
            [['day'], 'in', 'range' => [0, -1, -2, -3, -4]],
            [['day'], 'validateCreationDate'],
            [['type'], 'in', 'range' => [Meal::BREAKFAST, Meal::LUNCH, Meal::DINNER, Meal::OTHER]],
        ];
    }

    /**
     * How many days to subtract depending on the user selection
     *
     * @param $attribute
     * @param $params
     * @param $validator
     * @param $current
     * @return void
     * @throws \DateInvalidOperationException
     * @throws \DateMalformedIntervalStringException
     */
    public function validateCreationDate($attribute, $params, $validator, $current)
    {
        $this->date = new DateTime();
        $this->date = $this->date->sub(new DateInterval('P' . abs($current) . 'D'));
    }


    public function newFileName(): void
    {
        $this->filepath = (string)'uploads/' . Yii::$app->user->id . '-' . Uuid::uuid4() . '.' . $this->picture->extension;
    }

    public function getTypeList(): array
    {
        return [
            Meal::BREAKFAST => 'Breakfast',
            Meal::LUNCH => 'Lunch',
            Meal::DINNER => 'Dinner',
            Meal::OTHER => '🤷'
        ];
    }

    public function upload(): bool
    {
        if ($this->validate()) {
            $this->newFileName();
            $this->picture->saveAs('@frontend/web/' . $this->filepath);
            return true;
        } else {
            $errors = $this->getErrors();
            return false;
        }
    }
}