<?php

namespace common\models\search;

use common\models\User as UserModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SalesAgent;


class User extends UserModel
{
    public string $role = '';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['email', 'first_name', 'role'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserModel::find()
            ->joinWith('authAssignment', 'salesAgent');
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['salesAgentName'] = [
            'asc' => ['sales_agent_id' => SORT_ASC],
            'desc' => ['sales_agent_id' => SORT_DESC],
        ];
    
        $dataProvider->sort->attributes['role'] = [
            'asc' => ['auth_assignment.item_name' => SORT_ASC],
            'desc' => ['auth_assignment.item_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->salesAgentName)) {
            $salesAgent = SalesAgent::find()->where(['name' => $this->salesAgentName])->one();
            $this->sales_agent_id = $salesAgent ? $salesAgent->id : null;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'sales_agent_id' => $this->sales_agent_id,
            'auth_assignment.item_name' => $this->role
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}