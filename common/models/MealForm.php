<?php

namespace common\models;

use Ramsey\Uuid\Uuid;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class MealForm extends Model
{

    /**
     * @var UploadedFile
     */
    public $picture;
    public string $filepath;

    public function rules() {
        return [
            [['picture'], 'file', 'skipOnEmpty' => false],
            [['picture'], 'required'],
        ];
    }

    public function newFileName()
    {
        $this->filepath = (string)'uploads/' . Yii::$app->user->id . '-' . Uuid::uuid4() . '.' . $this->picture->extension;
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->newFileName();
            $this->picture->saveAs('@frontend/web/'.$this->filepath);
            return true;
        } else {
            return false;
        }
    }
}