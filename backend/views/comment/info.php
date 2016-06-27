<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\Hospital;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeNavigateSelectedName; */
/* @var $directoryStructureSearch */
/* @var $dataProvider */
/* @var $referrerUrl */
$this->title = '二级评论列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = Yii::$app->params;
backend\assets\AppAsset::register($this);
/*根据get参数判断是否是考卷添加试题*/
?>

<div class="modal-body">
    
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search-info', [
                'model' => $searchModel,
                'referrerUrl' => $referrerUrl,
            ]); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['delete'],
                'method' => 'post',
                'options' => ['class' => 'form-inline', 'id' => 'changeStatus'],
            ]); ?>
            <?= Html::input('hidden', 'type', 'enable', ['id' => 'typeForm']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                            return ['value' => $model->id];
                        }
                    ],
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '序号'
                    ],
                    'created_at:datetime',
                    [
                        'attribute' => 'real_name',
                        'value' =>
                            function ($model) {
                                $result = Member::findOne($model->uid);
                                $this->params['comment']['nickname'] = $result->nickname ?? '';
                                $this->params['comment']['username'] = $result->username ?? '';
                                return $result->real_name ?? '';
                            },
                    ],
                    [
                        'attribute' => 'nickname',
                        'value' =>
                            function ($model) {
                                return $this->params['comment']['nickname'];
                            },
                    ],
                    [
                        'attribute' => 'username',
                        'value' =>
                            function ($model) {
                                return $this->params['comment']['username'];
                            },
                    ],
                    [
                        'attribute' => 'to-real_name',
                        'value' =>
                            function ($model) {
                                $result = Member::findOne($model->reply_to_uid);
                                $this->params['comment']['to-nickname'] = $result->nickname ?? '';
                                $this->params['comment']['to-username'] = $result->username ?? '';
                                return $result->real_name ?? '';
                            },
                    ],
                    [
                        'attribute' => 'to-nickname',
                        'value' =>
                            function ($model) {
                                return $this->params['comment']['to-nickname'];
                            },
                    ],
                    [
                        'attribute' => 'to-username',
                        'value' =>
                            function ($model) {
                                return $this->params['comment']['to-username'];
                            },
                    ],
                    'content',
                    'comments',
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>




