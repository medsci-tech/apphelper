<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $dataModel common\models\ArticleData */

$this->title = '添加用户数据 ';
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];

?>
<div class="article-create">

    <?= $this->render('_form',['formName' => 'create']) ?>

</div>
