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
$referrer = Yii::$app->request->referrer ?? 'index';
?>
<div class="modal-body">
    <p>
        <?= Html::a('返回', $referrer, ['class' => 'btn btn-white']) ?>
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
                'actionCtrl' => 'img',
                'name' => 'Resource[imgurl]',
                'uploadPath' => 'image/resource',
                'imgMaxSize' => 2097152,/*文件限制2M*/
            ]);?>
            <div class="help-block"></div>
        </div>
        <div class="form-group">
            <label class="control-label">上传pdf(<font style="font-size: 12px;color: #ed5565;font-weight: 100;">如果是ppt格式，请手动转成pdf再上传</font>)</label>
            <?= $this->render('/webuploader/index2',[
                'name' => 'Resource[ppt_imgurl]',
                'uploadPath' => 'pdf',
                'actionCtrl' => 'pdf',
                'imgMaxSize' => 2097152,/*文件限制2M*/
            ]);?>
            <div class="help-block"></div>
        </div>
        <?= $form->field($model, 'videourl')->textInput(['placeholder' => '填写格式为：http://xx.com/xx/xx.mp4']) ?>
        <?= $form->field($model, 'content')->widget('kucha\ueditor\UEditor', ['options' => ['style' => '']]) ?>
        <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>

        <?= Html::a('返回', $referrer, ['class' => 'btn btn-white']) ?>
        <?= Html::button('确定', ['class' => 'btn btn-primary','id'=>'submitBtn']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$('#resource-rid').chosen({width: '100%'});
$('#resource-rids').chosen({width: '100%'});

//提交操作
$('#submitBtn').click(function() {
    var data = {};
    var elmeParent = '#resource';
    data.status = $(elmeParent + '-status').val();
    data.title = $(elmeParent + '-title').val();
    data.rid = $(elmeParent + '-rid').val();
    data.author = $(elmeParent + '-author').val();
    data.rids = $(elmeParent + '-rids').val();
    data.keyword = $(elmeParent + '-keyword').val();
    data.imgurl = $('[data-toggle="upload-saveInput"]').val();
    data.videourl = $(elmeParent + '-videourl').val();
    data.content = $(elmeParent + '-content').val();
    var id = $(elmeParent + '-id').val();
    var ppt_imgurl = $('[data-toggle="upload-saveInput-one"]');
    data.ppt_imgurl = getDataListForMime(ppt_imgurl);
    console.log(data);
    subActionAjaxForMime('post', 'form?id=' + id, {'Resource':data}, '$referrer');
});
JS;
$this->registerJs($js);
?>