<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */

$yiiApp = Yii::$app;
$this->title = '视频管理';
$this->params['breadcrumbs'][] = $this->title;
backend\assets\AppAsset::register($this);
?>
<div class="modal-body">
    <div class="box-body"><?php echo $this->render('_search', ['model' => $searchModel]); ?></div>
    <div class="box box-success">
        <div class="box-body">
            <?php
                $form = ActiveForm::begin([
                'action' => ['modify'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'modifyForm'],
            ]); ?>
            <?= Html::input('hidden', 'type', 'enable', ['id' => 'typeForm']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }

                    ],
                    ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'url',
                    'suffix',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}',
                        'header' => '操作',
                    ]
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- 弹出部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><label id="l_title">单位</label></h4>
            </div>
            <?=$this->render('form');?>
        </div>
    </div>
</div>

<?php
$js=<<<JS

  
JS;
$this->registerJs($js);
?>