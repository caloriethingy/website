<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\SalesAgent;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\search\User $searchModel */

$this->title = Yii::t('app', 'Users');
?>
<div class="user-index container1">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}{pager}",
        'tableOptions' => [
            'class' => 'custom-table',
        ],
        'headerRowOptions' => [
            'class' => 'table-header',
        ],
        'rowOptions' => [
            'class' => 'align-middle',
        ],
        'columns' => [
            'email:email',
            [
                'attribute' => 'status',
                'label' => 'Status',
                'value' => function ($model) {
                    return $model->getStatusName();
                },
                'filter' => [
                    User::STATUS_ACTIVE => 'Active',
                    User::STATUS_INACTIVE => 'Inactive',
                    User::STATUS_UNVERIFIED => 'Unverified',
                    User::STATUS_VERIFIED => 'Verified (not active)',
                ],
            ],
            [
                'attribute' => 'salesAgentName',
                'label' => Yii::t('app', 'Sales Agent'),
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->salesAgent 
                        ? Html::a($model->salesAgent->name, Url::to(['sales-agent/view', 'id' => $model->salesAgent->id])) 
                        : Yii::t('app', 'None assigned');
                },
                'filter' => \yii\helpers\ArrayHelper::map(SalesAgent::find()->all(), 'name', 'name'),
            ],
            [
                'attribute' => 'role',
                'label' => 'Role',
                'value' => function ($model) {
                    $roles = Yii::$app->authManager->getRolesByUser($model->id);
                    return !empty($roles) ? reset($roles)->name : null;
                },
                'filter' => \yii\helpers\ArrayHelper::map(
                    Yii::$app->authManager->getRoles(),
                    'name',
                    'name'
                ),
            ],
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
        'pager' => [
            'class' => 'yii\widgets\LinkPager',
            'nextPageLabel' => '►',
            'prevPageLabel' => '◄',
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination'],
        ],
    ]); ?>

</div>
