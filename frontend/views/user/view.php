<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->email;
\yii\web\YiiAsset::register($this);
?>

<div class="user-view mt-3">
    <div class="card shadow-sm rounded-lg p-4">
        <h2 class="mb-3"><?= Html::encode($this->title) ?></h2>

        <div class="d-flex gap-2 mb-4">
            <?php if (Yii::$app->user->can('updateDelete')): ?>
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this user?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <p><strong>ID:</strong> <?= Html::encode($model->id) ?></p>
                <p><strong>Email:</strong> <?= Html::encode($model->email) ?></p>
                <p><strong>Status:</strong> <?= Html::encode($model->statusName) ?></p>
                <p><strong>Role:</strong> <?= Html::encode($model->role) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Sales Agent:</strong> 
                    <?php if (!empty($model->salesAgent)): ?>
                        <?= Html::a(Html::encode($model->salesAgent->name), Url::to(['sales-agent/view', 'id' => $model->salesAgent->id]), ['class' => 'text-decoration-none']) ?>
                    <?php else: ?>
                        <?= Yii::t('app', 'None assigned') ?>
                    <?php endif; ?>
                </p>
                <p><strong>Created At:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at) ?></p>
                <p><strong>Updated At:</strong> <?= Yii::$app->formatter->asDatetime($model->updated_at) ?></p>
            </div>
        </div>
    </div>
</div>
