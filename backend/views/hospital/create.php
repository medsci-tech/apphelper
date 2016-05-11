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

$this->params['breadcrumbs'][] = ['label' => '单位', 'url' => ['index']];

?>
<div class="hospital-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
