<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $dataModel common\models\ArticleData */

$this->title = '用户数据 '.$model->real_name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
