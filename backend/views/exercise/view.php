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
            'type',
            'category',
            'question',
            'option',
            'answer',
            'keyword',
            'resolve',
            'created_at:datetime',
            'status',
        ],
    ]) ?>

</div>
