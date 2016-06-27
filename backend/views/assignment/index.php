<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\Assignment */

$yiiApp = Yii::$app;
$this->title = '管理员';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
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
                    'username',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template'=>'{view}  {update}',
                        'header' => '操作',
                        'buttons'=>[
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#myModal" data-toggle="modal"
                                names="'.$model->username.'"
                                id="'.$model->id.'"
                                 ></span>');
                            },
                        ]
                    ]
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

<!--    <p>-->
<!--        --><?//= Html::a('add', ['/user/create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->
<!---->
<!--	--><?php
//    Pjax::begin();
//    echo GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            [
//                'class' => 'yii\grid\DataColumn',
//                'attribute' => $usernameField,
//            ],
//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view} {update}',
//                'buttons' => [
//                    'update' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
//                        '/user/update',
//                        'id' => $model->id,
//                        ], [
//                            'title' => Yii::t('yii', 'Update'),
//                            'aria-label' => Yii::t('yii', 'Update'),
//                            'data-pjax' => '0',
//                        ]);
//                    },
//                    'view' => function ($url, $model) {
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', [
//                        'view',
//                        'id' => $model->id,
//                        ]);
//                    },
//                ],
//            ],
//        ],
//    ]);
//    Pjax::end();
//    ?>

</div>

<!-- 弹出曾部分 -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><label id="l_title">单位</label></h4>
            </div>
<!--            --><?//=$this->render('form', [
//                'model' => $model,
//            ]);?>
        </div>
    </div>
</div>