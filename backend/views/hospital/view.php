<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/28
 * Time: 17:34
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '单位', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospital-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'name',
                'label' => '省份',
                'value' => strip_tags(\yii\helpers\Markdown::process($model->province->name)),
                'format' => 'raw',
            ],
            [
                'attribute' => 'name',
                'label' => '城市',
                'value' => strip_tags(\yii\helpers\Markdown::process($model->city->name)),
                'format' => 'raw',
            ],
            [
                'attribute' => 'name',
                'label' => '县区',
                'value' => strip_tags(\yii\helpers\Markdown::process($model->area->name)),
                'format' => 'raw',
            ],
            'address',
        ],
    ]) ?>

</div>
