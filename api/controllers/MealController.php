<?php

namespace api\controllers;

use api\components\JwtAuth;
use common\models\Meal;
use common\models\MealForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\web\UploadedFile;

class MealController extends ActiveController
{
    public $modelClass = Meal::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtAuth::class,
        ];
        return $behaviors;
    }


    public function actionCreateMeal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new MealForm();
        $model->load(Yii::$app->request->post());
        $model->picture = UploadedFile::getInstance($model, 'picture');
        if ($model->upload()) {
            $id = \Yii::$app->gemini->mealInquiry($model);
            return array_merge(['meal' => Meal::findOne($id)], $this->actionGetDailySummary());
        }

        return ['error' => 'Failed to save meal'];
    }

    public function actionGetDailySummary()
    {
        // @TODO make this a common function of the user maybe?
        Yii::$app->response->format = Response::FORMAT_JSON;
        $startOfDay = strtotime('today midnight');
        $endOfDay = strtotime('tomorrow midnight') - 1;

        $today = Meal::find()
            ->select([
                'SUM(calories) AS calories',
                'SUM(protein) AS protein',
                'SUM(fat) AS fat',
                'SUM(carbohydrates) AS carbohydrates',
                'SUM(fiber) AS fiber'
            ])
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['between', 'created_at', $startOfDay, $endOfDay])
            ->asArray()
            ->one();

        return ['summary' => $today];
    }

    public function actions()
    {
        $actions = parent::actions();

        // Modify the index action
        $actions['index']['prepareDataProvider'] = function() {
            // Get the user_id from the JWT token
            $userId = Yii::$app->user->identity->id;

            // Create the query to filter meals by the authenticated user
            $query = Meal::find()->where(['user_id' => $userId]);

            // Return the data provider with the filtered query
            return new ActiveDataProvider([
                'query' => $query,
            ]);
        };

        return $actions;
    }
}