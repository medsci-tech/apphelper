<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->real_name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//var_dump($model);exit;
?>
<div class="modal-body">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('返回', Yii::$app->request->referrer ?? 'index', ['class' => 'btn btn-white']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'real_name',
            'sex',
            'username',
            'email',
            'province',
            'city',
            'area',
            'hospital_id',
            'rank_id',
            'created_at:datetime',
            'updated_at:datetime',
            'status',
        ],
    ]) ?>

</div>
