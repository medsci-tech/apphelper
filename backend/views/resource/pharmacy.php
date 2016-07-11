<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
use common\models\Hospital;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $treeNavigateSelectedName; */
/* @var $directoryStructureSearch */
/* @var $dataProvider */
/* @var $params */
$yiiApp = Yii::$app;
$this->title = '药店培训';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $yiiApp->params;
backend\assets\AppAsset::register($this);
/*根据get参数判断是否是考卷添加试题*/
?>
<!--树形视图--start-->
<div id="treeView" class="col-lg-2 modal-body"></div>
<!--树形视图--end-->

<div class="modal-body col-lg-10">
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel, 'attr_type' => 0]); ?>
        </div>
    </div>
    <div class="box box-success">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php
            $form = ActiveForm::begin([
                'action' => ['delete','redirect'=>'pharmacy'],
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
                    'title',
                    'hour',
                    'views',
                    'comments',
                    [
                        'attribute' => 'uid',
                        'value' =>
                            function ($model) {
                                $result = \common\models\User::findOne($model->uid);
                                return $result->username ?? '';
                            },
                    ],
                    [
                        'attribute' => 'recommend_status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['recStatusOption'][$model->recommend_status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'publish_status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['pubStatusOption'][$model->publish_status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ?? '';
                            },
                    ],
                    [
                        'attribute' => 'publish_time',
                        'value' =>
                            function ($model) {
                                return $model->publish_status == 1 ? date('Y-m-d H:i:s', $model->publish_time) : '';
                            },
                    ],
                    'created_at:datetime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update}',//只需要展示删除和更新
                        'buttons' => [
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" ></span>','update_pha?id='.$model->id);
                            }
                        ]

                    ],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$js=<<<JS
    
    /*树形结构初始化*/
	var initSelectableTree = function() {
		return $('#treeView').treeview({
		    levels: 1,
		    onSubmitFormId: 'search-form',
		    onSubmitInputValue: 'resource-rid',
			data: $directoryStructureSearch
		});
	};
	var selectableTree = initSelectableTree();
	var findSelectableNodes = function() {
		return selectableTree.treeview('search', [ '$treeNavigateSelectedName', { ignoreCase: false, exactMatch: false } ]);
	};
	var selectableNodes = findSelectableNodes();

JS;
$this->registerJs($js);
?>



