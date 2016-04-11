<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/28
 * Time: 12:00
 */

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Hospital */

$this->title = '添加单位';
$this->params['breadcrumbs'][] = ['label' => '单位', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hospital-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>