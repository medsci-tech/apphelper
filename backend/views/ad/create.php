<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 17:22
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Hospital */

$this->title = '添加轮播图';
$this->params['breadcrumbs'][] = ['label' => '单位', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
