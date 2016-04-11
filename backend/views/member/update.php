<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $dataModel common\models\ArticleData */

$this->title = '更新用户: '.$model->real_name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->real_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
