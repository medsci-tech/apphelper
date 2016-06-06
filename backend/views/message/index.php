<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 11:42
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$yiiApp = Yii::$app;
$this->title = '消息推送';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
backend\assets\AppAsset::register($this);
?>

<p></p>
<div class="modal-body">
    <div class="box-body">
        <button id="btnAdd" type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
            <span class="glyphicon glyphicon-plus"></span>添加
        </button>
    </div>
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
                    'title',
                    'uid',
                    [
                        'attribute' => 'type',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['sendType'][$model->type];
                                return $result ?? '';
                            },
                    ],
                    'created_at',
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['sendOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    'send_at',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}  {update}',
                        'header' => '操作',
                        'buttons'=>[
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#myModal" data-toggle="modal"
                                title="'.$model->title.'"
                                content="'.$model->content.'"
                                id="'.$model->id.'"
                                uid="'.$model->uid.'"
                                type="'.$model->type.'"
                                link_url="'.$model->link_url.'"
                                 ></span>');
                            },
                        ]
                    ]
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- 弹出曾部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><label id="l_title">添加</label></h4>
            </div>
<!--            --><?//=$this->render('form', [
//                'model' => $model,
//            ]);?>
        </div>
    </div>
</div>
</div>
