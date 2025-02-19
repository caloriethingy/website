<?php

namespace common\models\search;

use common\models\Meal;
use Yii;
use yii\data\ActiveDataProvider;

class MealSearch extends Meal
{

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Meal::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // ALWAYS filter by user_id that is signed in
        $query->andFilterWhere(['user_id' => Yii::$app->user->id]);

        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }
}