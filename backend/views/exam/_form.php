<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
//var_dump($model->id);exit;
?>
<?php
$form = ActiveForm::begin([
    'action' => ['form'],
    'method' => 'post',
    'options' => ['id' => 'tableForm'],
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'type')->dropDownList(Yii::$app->params['exam']['type']) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'minutes')->textInput() ?>
    <?= $form->field($model, 'imgUrl')->textInput() ?>
    <div class="form-group">
        <label class="control-label">试题</label>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>序号</th>
                    <th>类型</th>
                    <th>题目</th>
                    <th>选项</th>
                    <th>答案</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody id="examListBody">
            </tbody>
        </table>
        <button id="add-exercise" type="button" class="btn btn-info btn-sm">添加</button>
    </div>
    <?= $form->field($model, 'about')->textarea() ?>
    <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['statusOption']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$js = <<<JS
    /*删除试题*/
    $('#examListBody').on('click','.delThisOption',function() {
       delThisRowOptionForMime('#examListBody',this);
    });
    /*添加试题*/
    $('#add-exercise').click(function() {
        layer.open({
            type: 2,
            title: '添加试题',
            area: ['800px', '600px'],
            fix: false, //不固定
            maxmin: true,
            content: '/exercise/index?hiboyiamalayer=itisevident'
        });
    });
JS;
$this->registerJs($js);
?>
