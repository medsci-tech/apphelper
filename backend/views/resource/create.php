<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $directoryStructureList */

$this->title = '添加资源';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-body">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('返回', Yii::$app->request->referrer ?? 'index', ['class' => 'btn btn-white']) ?>
    </p>

    <?php
    $form = ActiveForm::begin([
        'action' => ['form'],
        'method' => 'post',
    ]); ?>
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'rid')->dropDownList($directoryStructureList) ?>
        <?= $form->field($model, 'author')->textInput() ?>
        <?= $form->field($model, 'rids')->textInput() ?>
        <?= $form->field($model, 'keyword')->textInput(['placeholder' => '关键词可添加多个，用“|”分开']) ?>
        <div class="form-group">
            <label class="control-label">缩略图</label>
            <?= $this->render('/webuploader/index',[
                'name' => 'Resource[imgurl]',
                'imgMaxSize' => 2097152,/*文件限制2M*/
            ]);?>
            <div class="help-block"></div>
        </div>
        <?= $form->field($model, 'content')->widget('kucha\ueditor\UEditor', ['options' => ['style' => '']]) ?>
        <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>

        <?= Html::a('返回', Yii::$app->request->referrer ?? 'index', ['class' => 'btn btn-white']) ?>
        <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>