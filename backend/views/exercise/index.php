<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Region;
use common\models\Hospital;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Article */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProvider */
/* @var $params */

$this->title = '题库';
$this->params['breadcrumbs'][] = $this->title;
$this->params['params'] = $params;


?>
<?= $this->render('/common/treeNavigate');?>

<div class="modal-body col-lg-10">
    <div class="box box-success">
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
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
                'options' => ['class' => 'form-inline', 'id' => 'delForm'],
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
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'type',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['exercise']['type'][$model->type];
                                return $result ? $result : '';
                            },
                    ],
                    'question',
                    [
                        'attribute' => 'option',
//                        'label'=>'选项',
                        'value' => function ($model) {
                            return count(unserialize($model->option));
                        }
                    ],
                    'answer',
                    [
                        'attribute' => 'status',
                        'value' =>
                            function ($model) {
                                $result = $this->params['params']['statusOption'][$model->status];
                                return $result ? $result : '';
                            },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'template' => '{update} {delete}',//只需要展示删除和更新
                        'buttons' => [
                            'update'=> function ($url, $model, $key) {
                                return Html::a('<span name="saveData" class="glyphicon glyphicon-pencil" data-target="#formModal" data-toggle="modal"
                                data-id="'.$model->id.'"
                                data-type="'.$model->type.'"
                                data-category="'.$model->category.'"
                                data-question="'.$model->question.'"
                                data-option="'.$model->option.'"
                                data-answer="'.$model->answer.'"
                                data-keyword="'.$model->keyword.'"
                                data-resolve="'.$model->resolve.'"
                                data-status="'.$model->status.'"
                                 ></span>');
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<!-- 弹出层 -->
<div class="modal inmodal" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加</h4>
            </div>
            <?= $this->render('_form', [
                'model' => $searchModel,
            ]); ?>
        </div>
    </div>
</div>

<?php
$js=<<<JS
$(document).ready(function(){
    $("span[name='saveData']").click(function(){
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var category = $(this).attr('data-category');
        var question = $(this).attr('data-question');
        var option = $(this).attr('data-option');
        var answer = $(this).attr('data-answer');
        var keyword = $(this).attr('data-keyword');
        var resolve = $(this).attr('data-resolve');
        var status = $(this).attr('data-status');
        /* 编辑初始化 */
        $('#formModal #tableForm').attr('action','/exercise/form?id='+id);
        $('#formModal #exercise-type').val(type);
        $('#formModal #exercise-category').val(category);
        $('#formModal #exercise-question').val(question);
        $('#formModal #exercise-option').val(option);
        $('#formModal #exercise-answer').val(answer);
        $('#formModal #exercise-keyword').val(keyword);
        $('#formModal #exercise-resolve').val(resolve);
        $('#formModal #exercise-status').val(status);

    });
    $("#createBtn").click(function(){
        var defaltData = ''; 
        $('#formModal #tableForm').attr('action','/exercise/form');
        $('#formModal #exercise-type').val(defaltData);
        $('#formModal #exercise-category').val(defaltData);
        $('#formModal #exercise-question').val(defaltData);
        $('#formModal #exercise-option').val(defaltData);
        $('#formModal #exercise-answer').val(defaltData);
        $('#formModal #exercise-keyword').val(defaltData);
        $('#formModal #exercise-resolve').val(defaltData);
        $('#formModal #exercise-status').val(defaltData);
    });
});
JS;
$this->registerJs($js);
?>



