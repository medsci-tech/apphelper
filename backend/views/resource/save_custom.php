<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $title */
/* @var $directoryStructureList */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <p>
        <?= Html::a('返回', Yii::$app->request->referrer ?? 'index', ['class' => 'btn btn-white']) ?>
    </p>
    <?php
    $form = ActiveForm::begin([
        'action' => ['form'],
        'method' => 'post',
    ]); ?>
        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'rid')->dropDownList($directoryStructureList) ?>
        <?= $form->field($model, 'author')->textInput() ?>
        <?= $form->field($model, 'rids')->dropDownList($directoryStructureList,['multiple'=>1]) ?>
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

<?php
$js = <<<JS
$('#resource-rid').chosen({width: '100%'});
$('#resource-rids').chosen({width: '100%',default_multiple_text: '123'});
JS;
$this->registerJs($js);
?>